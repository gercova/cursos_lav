<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentExamsController extends Controller {

    public  function __construct() {
        $this->middleware(['auth:sanctum', 'student', 'prevent.back']);
    }

    public function index(): View {
        return view('student.exams.index');
    }

    public function show($courseId): View {
        $enrollment = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $courseId)
            ->firstOrFail();

        $exam = Exam::with('questions')
            ->where('course_id', $courseId)
            ->where('is_active', true)
            ->firstOrFail();

        $previousAttempts = ExamAttempt::where('user_id', Auth::id())
            ->where('exam_id', $exam->id)
            ->orderBy('attempt_number', 'desc')
            ->get();

        $canTakeExam = $previousAttempts->count() < $exam->max_attempts;

        return view('student.exam.show', compact('exam', 'previousAttempts', 'canTakeExam'));
    }

    public function start($courseId, Request $request) {
        $enrollment = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $courseId)
            ->firstOrFail();

        $exam = Exam::where('course_id', $courseId)
            ->where('is_active', true)
            ->firstOrFail();

        // Verificar intentos máximos
        $attemptCount = ExamAttempt::where('user_id', Auth::id())
            ->where('exam_id', $exam->id)
            ->count();

        if ($attemptCount >= $exam->max_attempts) {
            return redirect()->back()->with('error', 'Has alcanzado el número máximo de intentos para este examen.');
        }

        // Verificar si ya aprobó
        $hasPassed = ExamAttempt::where('user_id', Auth::id())
            ->where('exam_id', $exam->id)
            ->where('passed', true)
            ->exists();

        if ($hasPassed) {
            return redirect()->back()->with('info', 'Ya has aprobado este examen. Puedes ver tu certificado en la sección correspondiente.');
        }

        // Crear nuevo intento
        $attempt = ExamAttempt::create([
            'exam_id' => $exam->id,
            'user_id' => Auth::id(),
            'attempt_number' => $attemptCount + 1,
            'started_at' => now(),
            'answers' => [],
        ]);

        return redirect()->route('student.exams.take', $attempt->id);
    }

    public function take($attemptId) {
        $attempt = ExamAttempt::with('exam.questions')
            ->where('user_id', Auth::id())
            ->findOrFail($attemptId);

        if ($attempt->completed_at) {
            return redirect()->route('student.exams.result', $attempt->id);
        }

        if ($attempt->isExpired()) {
            $attempt->update([
                'completed_at' => now(),
                'score' => 0,
                'passed' => false,
            ]);

            return redirect()->route('student.exams.result', $attempt->id);
        }

        $questions = $attempt->exam->getRandomQuestions();

        return view('student.exam.take', compact('attempt', 'questions'));
    }

    public function submit(Request $request, $attemptId) {
        $attempt = ExamAttempt::with('exam')
            ->where('user_id', Auth::id())
            ->findOrFail($attemptId);

        if ($attempt->completed_at) {
            return redirect()->route('student.exams.result', $attempt->id);
        }

        $answers        = $request->answers ?? [];
        $score          = 0;
        $totalPoints    = 0;

        // Calcular puntaje
        foreach ($attempt->exam->questions as $question) {
            $totalPoints += $question->points;

            if (isset($answers[$question->id])) {
                $userAnswer     = $answers[$question->id];
                $correctAnswer  = $question->correct_answer;

                if ($userAnswer == $correctAnswer) {
                    $score += $question->points;
                }
            }
        }

        $finalScore = $totalPoints > 0 ? ($score / $totalPoints) * 20 : 0;
        $passed = $finalScore >= $attempt->exam->passing_score;

        $attempt->update([
            'completed_at'  => now(),
            'score'         => $finalScore,
            'passed'        => $passed,
            'answers'       => $answers,
        ]);

        // Generar certificado si aprobó
        if ($passed) {
            $courseId = $attempt->exam->course_id;

            // Verificar si ya existe certificado para este curso
            $existingCertificate = Certificate::where('user_id', Auth::id())
                ->where('course_id', $courseId)
                ->first();

            if (!$existingCertificate) {
                Certificate::create([
                    'user_id'               => Auth::id(),
                    'course_id'             => $courseId,
                    'exam_attempt_id'       => $attempt->id,
                    'certificate_code'      => Certificate::generateVerificationCode(),
                    'certificate_number'    => Certificate::generateCertificateNumber(),
                    'issue_date'            => now(),
                    'expiry_date'           => now()->addYears(2),
                    'total_hours'           => $attempt->exam->course->duration ?? 4.0,
                    'download_count'        => 0,
                ]);
            }
        }

        return redirect()->route('student.exams.result', $attempt->id);
    }

    public function result($attemptId) {
        $attempt = ExamAttempt::with(['exam', 'certificate'])->where('user_id', Auth::id())->findOrFail($attemptId);
        return view('student.exam.result', compact('attempt'));
    }

    public function getExamDataApi(Request $request) {
        try {
            $userId = Auth::id();

            // Obtener cursos inscritos del usuario
            $enrolledCourses = Enrollment::where('user_id', $userId)
                ->with(['course' => function($query) {
                    $query->select('id', 'title', 'slug', 'description', 'duration');
                }])
                ->get();

            $availableExams = [];
            $completedExams = [];
            $recentAttempts = [];

            // Procesar cada curso inscrito
            foreach ($enrolledCourses as $enrollment) {
                $course = $enrollment->course;

                // Buscar examen del curso
                $exam = Exam::where('course_id', $course->id)
                    ->where('is_active', true)
                    ->first();

                if (!$exam) continue;

                // Obtener intentos del usuario para este examen
                $attempts = ExamAttempt::where('user_id', $userId)
                    ->where('exam_id', $exam->id)
                    ->orderBy('attempt_number', 'desc')
                    ->get();

                // Preparar datos del examen disponible
                $attemptsUsed = $attempts->count();
                $canTakeExam = $attemptsUsed < $exam->max_attempts;
                $hasPassed = $attempts->contains('passed', true);

                $availableExams[] = [
                    'id' => $exam->id,
                    'course_id' => $course->id,
                    'course_title' => $course->title,
                    'description' => $exam->description,
                    'duration' => $exam->duration,
                    'passing_score' => $exam->passing_score,
                    'max_attempts' => $exam->max_attempts,
                    'attempts_used' => $attemptsUsed,
                    'has_previous_attempts' => $attemptsUsed > 0,
                    'has_passed' => $hasPassed,
                    'can_take' => $canTakeExam && !$hasPassed,
                    'reason' => $hasPassed ? 'Ya aprobaste este examen' :
                                ($attemptsUsed >= $exam->max_attempts ? 'Has agotado los intentos' : null),
                    'color' => $this->getCourseColor($course->id),
                    'icon' => $this->getCourseIcon($course->id),
                    'status' => $hasPassed ? 'passed' :
                                ($canTakeExam ? 'pending' : 'failed')
                ];

                // Preparar intentos recientes (últimos 5 por examen)
                $recent = $attempts->take(5)->map(function($attempt) use ($course, $exam) {
                    return [
                        'attempt_id' => $attempt->id,
                        'course_title' => $course->title,
                        'attempt_number' => $attempt->attempt_number,
                        'score' => number_format($attempt->score, 1),
                        'passed' => $attempt->passed,
                        'date_formatted' => $attempt->completed_at ? $attempt->completed_at->format('d/m/Y') : 'En curso',
                        'certificate_id' => $attempt->certificate ? $attempt->certificate->id : null
                    ];
                });

                $recentAttempts = array_merge($recentAttempts, $recent->toArray());

                // Preparar exámenes completados
                foreach ($attempts as $attempt) {
                    if ($attempt->completed_at) {
                        $completedExams[] = [
                            'exam_id' => $exam->id,
                            'exam_title' => $exam->title,
                            'course_id' => $course->id,
                            'course_title' => $course->title,
                            'attempt_id' => $attempt->id,
                            'attempt_number' => $attempt->attempt_number,
                            'score' => number_format($attempt->score, 1),
                            'passed' => $attempt->passed,
                            'date_formatted' => $attempt->completed_at->format('d/m/Y'),
                            'time_formatted' => $attempt->completed_at->format('H:i'),
                            'can_retake' => $attemptsUsed < $exam->max_attempts && !$attempt->passed,
                            'certificate_id' => $attempt->certificate ? $attempt->certificate->id : null,
                            'color' => $this->getCourseColor($course->id),
                            'icon' => $this->getCourseIcon($course->id)
                        ];
                    }
                }
            }

            // Ordenar intentos recientes por fecha
            usort($recentAttempts, function($a, $b) {
                return strtotime($b['date_formatted']) - strtotime($a['date_formatted']);
            });

            // Ordenar exámenes completados por fecha
            usort($completedExams, function($a, $b) {
                return strtotime($b['date_formatted'] . ' ' . $b['time_formatted']) -
                       strtotime($a['date_formatted'] . ' ' . $a['time_formatted']);
            });

            // Calcular estadísticas
            $stats = [
                'total' => count($availableExams),
                'passed' => collect($availableExams)->where('has_passed', true)->count(),
                'pending' => collect($availableExams)->where('can_take', true)->count(),
                'available' => collect($availableExams)->where('can_take', true)->count(),
                'remainingAttempts' => collect($availableExams)->sum(function($exam) {
                    return max(0, $exam['max_attempts'] - $exam['attempts_used']);
                })
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'available' => $availableExams,
                'recentAttempts' => array_slice($recentAttempts, 0, 10), // Últimos 10
                'completed' => $completedExams,
                'message' => 'Datos cargados correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los datos: ' . $e->getMessage(),
                'stats' => ['total' => 0, 'passed' => 0, 'pending' => 0, 'available' => 0, 'remainingAttempts' => 0],
                'available' => [],
                'recentAttempts' => [],
                'completed' => []
            ], 500);
        }
    }

    /**
     * Alias para la API desde web
     */
    public function getExamData(Request $request) {
        return $this->getExamDataApi($request);
    }

    private function getCourseColor($courseId): string {
        $colors = ['blue', 'emerald', 'amber', 'purple', 'rose', 'indigo', 'cyan', 'lime'];
        $index  = $courseId % count($colors);
        return $colors[$index];
    }

    /**
     * Obtener icono para un curso (puedes personalizar esta lógica)
     */
    private function getCourseIcon($courseId): string {
        $icons = [
            'file-alt', 'book', 'graduation-cap', 'laptop-code',
            'chart-bar', 'flask', 'bullhorn', 'users',
            'lightbulb', 'shield-alt', 'briefcase', 'globe'
        ];
        $index = $courseId % count($icons);
        return $icons[$index];
    }
}
