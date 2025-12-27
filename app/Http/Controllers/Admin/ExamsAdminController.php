<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExamValidate;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExamsAdminController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'admin', 'prevent.back']);
    }

    public function index(Request $request): View {
        $query = Exam::with(['course.category'])
            ->withCount(['questions', 'examAttempts as attempts_count'])
            ->withCount(['examAttempts as passed_count' => function($query) {
                $query->where('passed', true);
            }]);

        // Búsqueda
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtro por estado
        if ($status = $request->input('status')) {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filtro por curso
        if ($course = $request->input('course')) {
            $query->where('course_id', $course);
        }

        // Ordenar
        $query->orderBy('created_at', 'desc');
        $exams = $query->paginate(10);
        $courses = Course::where('is_active', true)
            ->with('category')
            ->orderBy('title')
            ->get();

        return view('admin.exams.index', compact('exams', 'courses'));
    }

    public function create(): View {
        $courses = Course::where('is_active', true)
            ->with('category')
            ->orderBy('title')
            ->get();

        return view('admin.exams.create', compact('courses'));
    }

    public function edit(Exam $exam): View {
        $courses = Course::where('is_active', true)
            ->with('category')
            ->orderBy('title')
            ->get();

        // Cargar estadísticas
        $exam->loadCount(['questions', 'examAttempts']);
        $exam->passed_count     = $exam->examAttempts()->where('passed', true)->count();
        $exam->attempts_count   = $exam->examAttempts()->count();
        return view('admin.exams.edit', compact('exam', 'courses'));
    }

    public function duplicate(Exam $exam): JsonResponse {
        try {
            // Duplicar examen
            $newExam = $exam->replicate();
            $newExam->title = $exam->title . ' (Copia)';
            $newExam->save();

            // Duplicar preguntas
            foreach ($exam->questions as $question) {
                $newQuestion            = $question->replicate();
                $newQuestion->exam_id   = $newExam->id;
                $newQuestion->save();
            }

            return response()->json([
                'success'       => true,
                'message'       => 'Examen duplicado exitosamente',
                'new_exam_id'   => $newExam->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al duplicar el examen'
            ], 500);
        }
    }

    public function store(ExamValidate $request) {
        $validated = $request->validated();
        $exam = Exam::create($validated);

        return response()->json([
            'success'   => true,
            'message'   => 'Examen creado exitosamente',
            'exam'      => $exam
        ]);
    }

    public function update(ExamValidate $request, Exam $exam) {
        $validated = $request->validated();
        $exam->update($validated);

        if ($request->type === 'window') {
            return redirect()->route('admin.exams.edit', $exam)->with('success', 'Examen actualizado exitosamente');
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Examen actualizado exitosamente',
            'exam'      => $exam
        ]);
    }

    public function destroy(Exam $exam): JsonResponse {
        $exam->delete();
        return response()->json([
            'success' => true,
            'message' => 'Examen eliminado exitosamente'
        ]);
    }

    public function toggleStatus(Exam $exam): JsonResponse {
        $exam->update(['is_active' => !$exam->is_active]);

        return response()->json([
            'success'   => true,
            'is_active' => $exam->is_active
        ]);
    }

    public function show(Exam $exam): JsonResponse {
        return response()->json($exam);
    }

    public function questions(Exam $exam, Request $request): View {
        // Validamos la entrada para seguridad extra (opcional pero recomendado)
        $request->validate([
            'type'      => ['nullable', Rule::in(['multiple_choice', 'true_false'])],
            'search'    => 'nullable|string|max:255',
        ]);

        $questions = $exam->questions()
            // Búsqueda
            ->when($request->search, function ($query, $search) {
                $query->where('question', 'like', "%{$search}%");
            })
            // Filtro por tipo (Laravel pasa automáticamente el valor si existe)
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            })
            // Ordenamiento
            ->orderBy('order')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.exams.questions', compact('exam', 'questions'));
    }

    public function results(Exam $exam): View {
        // Cargamos el examen y sus intentos con la información del usuario
        $exam->load(['examAttempts.user' => function($query) {
            $query->select('id', 'name', 'email'); // Seleccionar solo campos necesarios
        }]);

        $results = $exam->examAttempts()->with('user')->latest()->paginate(15);
        return view('admin.exams.results', compact('exam', 'results'));
    }

    /**
     * Obtener detalles completos de un intento específico
     */
    public function attemptDetails($id): JsonResponse {
        try {
            $attempt = ExamAttempt::with([
                'user:id,name,email',
                'exam:id,title,passing_score',
                'exam.questions:id,exam_id,question,correct_answer,type,points'
            ])->findOrFail($id);

            // Calcular total de puntos del examen
            $totalPoints = $attempt->exam->questions->sum('points');

            // Preparar respuestas del estudiante con información detallada
            $answers = [];
            if ($attempt->answers && is_array($attempt->answers)) {
                foreach ($attempt->answers as $questionId => $studentAnswer) {
                    $question = $attempt->exam->questions->firstWhere('id', $questionId);

                    if ($question) {
                        $isCorrect = false;

                        // Comparar respuestas según el tipo de pregunta
                        if ($question->type === 'multiple_choice') {
                            $isCorrect = (int)$studentAnswer === (int)$question->correct_answer;
                        } elseif ($question->type === 'true_false') {
                            $isCorrect = $studentAnswer === $question->correct_answer;
                        }

                        // Formatear respuestas para mostrar
                        $formattedStudentAnswer = $this->formatAnswer($studentAnswer, $question);
                        $formattedCorrectAnswer = $this->formatAnswer($question->correct_answer, $question);

                        $answers[] = [
                            'question_id'       => $questionId,
                            'question_text'     => $question->question,
                            'question_type'     => $question->type,
                            'question_points'   => $question->points,
                            'student_answer'    => $formattedStudentAnswer,
                            'correct_answer'    => $formattedCorrectAnswer,
                            'is_correct'        => $isCorrect,
                        ];
                    }
                }
            }

            // Calcular duración del intento
            $duration = null;
            if ($attempt->started_at && $attempt->completed_at) {
                $duration = $attempt->completed_at->diffInSeconds($attempt->started_at);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $attempt->id,
                    'user' => [
                        'id'    => $attempt->user->id,
                        'name'  => $attempt->user->name,
                        'email' => $attempt->user->email,
                    ],
                    'exam' => [
                        'id'            => $attempt->exam->id,
                        'title'         => $attempt->exam->title,
                        'passing_score' => $attempt->exam->passing_score,
                    ],
                    'score'             => $attempt->score,
                    'total_points'      => $totalPoints,
                    'passed'            => $attempt->passed,
                    'attempt_number'    => $attempt->attempt_number,
                    'started_at'        => $attempt->started_at ? $attempt->started_at->format('Y-m-d H:i:s') : null,
                    'completed_at'      => $attempt->completed_at ? $attempt->completed_at->format('Y-m-d H:i:s') : null,
                    'duration'          => $duration,
                    'answers'           => $answers,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching attempt details: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los detalles del intento',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Método auxiliar para formatear respuestas
     */
    private function formatAnswer($answer, $question) {
        if ($question->type === 'multiple_choice') {
            // Convertir índice a letra (0 = A, 1 = B, etc.)
            $index = (int)$answer;
            if (isset($question->options[$index])) {
                return $question->options[$index];
            }
            return 'Opción ' . chr(65 + $index);
        } elseif ($question->type === 'true_false') {
            return $answer === 'true' ? 'Verdadero' : 'Falso';
        }

        return $answer;
    }

    /**
     * Exportar resultados del examen a CSV
     */
    public function exportResults(Exam $exam, Request $request): BinaryFileResponse {
        try {
            // Aplicar filtros si existen
            $query = $exam->examAttempts()->with('user:id,name,email');

            // Filtrar por búsqueda
            if ($search = $request->input('search')) {
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Filtrar por estado
            if ($status = $request->input('status')) {
                if ($status === 'passed') {
                    $query->where('passed', true);
                } elseif ($status === 'failed') {
                    $query->where('passed', false);
                }
            }

            $attempts = $query->orderBy('created_at', 'desc')->get();

            // Crear archivo CSV temporal
            $filename = 'resultados_examen_' . Str::slug($exam->title) . '_' . now()->format('Y-m-d_H-i') . '.csv';
            $filepath = storage_path('app/temp/' . $filename);

            // Asegurar que exista el directorio
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            $handle = fopen($filepath, 'w');

            // Agregar BOM para correcta visualización de acentos en Excel
            fwrite($handle, "\xEF\xBB\xBF");

            // Encabezados del CSV
            $headers = [
                'ID Intento',
                'Nombre del Estudiante',
                'Email',
                'Número de Intento',
                'Fecha y Hora',
                'Puntuación',
                'Puntuación Máxima',
                'Porcentaje',
                'Estado',
                'Aprobado/Reprobado',
                'Tiempo Inicio',
                'Tiempo Fin',
                'Duración (minutos)',
            ];

            fputcsv($handle, $headers);

            // Datos
            foreach ($attempts as $attempt) {
                $duration = null;
                if ($attempt->started_at && $attempt->completed_at) {
                    $duration = $attempt->completed_at->diffInMinutes($attempt->started_at);
                }

                $percentage = $attempt->total_points > 0 ? ($attempt->score / $attempt->total_points * 100) : 0;

                $row = [
                    $attempt->id,
                    $attempt->user->name ?? 'N/A',
                    $attempt->user->email ?? 'N/A',
                    $attempt->attempt_number,
                    $attempt->created_at->format('d/m/Y H:i:s'),
                    $attempt->score,
                    $attempt->total_points,
                    number_format($percentage, 2) . '%',
                    $attempt->passed ? 'Aprobado' : 'Reprobado',
                    $attempt->passed ? 'Si' : 'No',
                    $attempt->started_at ? $attempt->started_at->format('d/m/Y H:i:s') : 'N/A',
                    $attempt->completed_at ? $attempt->completed_at->format('d/m/Y H:i:s') : 'N/A',
                    $duration ? number_format($duration, 2) : 'N/A',
                ];

                fputcsv($handle, $row);
            }

            fclose($handle);

            // Crear respuesta de descarga
            $headers = [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            return response()->download($filepath, $filename, $headers)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Error exporting results: ' . $e->getMessage());

            // Si hay error, redirigir con mensaje
            return redirect()->route('admin.exams.results', $exam)->with('error', 'Error al exportar los resultados: ' . $e->getMessage());
        }
    }

    /**
     * Método para estadísticas del examen (opcional, para mejorar el dashboard)
     */
    public function examStatistics(Exam $exam): JsonResponse {
        try {
            $attempts = $exam->examAttempts()->with('user')->get();

            // Calcular estadísticas
            $totalAttempts = $attempts->count();
            $passedCount = $attempts->where('passed', true)->count();
            $failedCount = $totalAttempts - $passedCount;
            $passRate = $totalAttempts > 0 ? ($passedCount / $totalAttempts * 100) : 0;

            // Puntuaciones
            $scores = $attempts->pluck('score')->filter();
            $averageScore = $scores->avg() ?? 0;
            $highestScore = $scores->max() ?? 0;
            $lowestScore = $scores->min() ?? 0;

            // Distribución por rangos
            $scoreDistribution = [
                '0-49'      => 0,
                '50-69'     => 0,
                '70-89'     => 0,
                '90-100'    => 0,
            ];

            foreach ($attempts as $attempt) {
                if ($attempt->total_points > 0) {
                    $percentage = ($attempt->score / $attempt->total_points) * 100;
                    if ($percentage < 50) $scoreDistribution['0-49']++;
                    elseif ($percentage < 70) $scoreDistribution['50-69']++;
                    elseif ($percentage < 90) $scoreDistribution['70-89']++;
                    else $scoreDistribution['90-100']++;
                }
            }

            // Tiempo promedio de completación
            $completedAttempts = $attempts->filter(function($a) {
                return $a->started_at && $a->completed_at;
            });

            $averageTime = null;
            if ($completedAttempts->count() > 0) {
                $totalSeconds = 0;
                foreach ($completedAttempts as $attempt) {
                    $totalSeconds += $attempt->completed_at->diffInSeconds($attempt->started_at);
                }
                $averageTime = floor($totalSeconds / $completedAttempts->count() / 60);
            }

            // Top 5 estudiantes
            $topStudents = $attempts->sortByDesc('score')->take(5)->map(function($attempt) {
                return [
                    'name' => $attempt->user->name ?? 'N/A',
                    'score' => $attempt->score,
                    'percentage' => $attempt->total_points > 0 ?
                        number_format(($attempt->score / $attempt->total_points) * 100, 1) . '%' : '0%',
                ];
            })->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_attempts'        => $totalAttempts,
                    'passed_count'          => $passedCount,
                    'failed_count'          => $failedCount,
                    'pass_rate'             => number_format($passRate, 2),
                    'average_score'         => number_format($averageScore, 2),
                    'highest_score'         => $highestScore,
                    'lowest_score'          => $lowestScore,
                    'score_distribution'    => $scoreDistribution,
                    'average_time_minutes'  => $averageTime,
                    'top_students'          => $topStudents,
                    'recent_attempts'       => $attempts->take(10)->map(function($attempt) {
                        return [
                            'student'   => $attempt->user->name ?? 'N/A',
                            'score'     => $attempt->score,
                            'passed'    => $attempt->passed,
                            'date'      => $attempt->created_at->format('d/m/Y H:i'),
                        ];
                    })->values(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching exam statistics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
