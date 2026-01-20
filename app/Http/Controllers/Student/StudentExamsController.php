<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentExamsController extends Controller {

    public  function __construct() {
        $this->middleware(['auth:sanctum', 'student', 'prevent.back']);
    }

    /**
     * Listar exámenes del estudiante
     */
    public function index(): View {
        $user = Auth::user();

        // Obtener cursos inscritos del estudiante
        $enrolledCourses = $user->enrollments()
            ->whereHas('course', function($query) {
                $query->where('is_active', true);
            })
            ->with(['course.exams' => function($query) {
                $query->where('is_active', true);
            }])
            ->get()
            ->pluck('course.exams')
            ->flatten()
            ->unique('id');

        // Obtener todos los intentos del estudiante
        $attempts = ExamAttempt::where('user_id', $user->id)
            ->with('exam')
            ->get()
            ->groupBy('exam_id');

        // Separar exámenes
        $pendingExams   = collect();
        $completedExams = collect();

        foreach ($enrolledCourses as $exam) {
            $examAttempts = $attempts->get($exam->id) ?? collect();

            // Buscar intentos completados
            $completedAttempt = $examAttempts->first(function($attempt) {
                return $attempt->completed_at !== null;
            });

            if ($completedAttempt) {
                // Examen completado
                $exam->attempt          = $completedAttempt;
                $exam->attempt_count    = $examAttempts->count();
                $exam->can_retake       = $this->canRetakeExam($exam, $user->id);
                $completedExams->push($exam);
            } else {
                // Examen pendiente
                $exam->attempt_count = $examAttempts->count();
                $pendingExams->push($exam);
            }
        }

        return view('student.exams.index', compact('pendingExams', 'completedExams'));
    }

    /**
     * Mostrar formulario de examen
     */
    public function show($id): View|RedirectResponse {
        $user = Auth::user();
        $exam = Exam::with('course', 'questions')->findOrFail($id);

        // Verificar inscripción
        if (!$user->enrollments()->where('course_id', $exam->course_id)->exists()) {
            return redirect()->route('student.exams.index')->with('error', 'No estás inscrito en este curso.');
        }

        // Verificar intentos máximos
        $attemptCount = ExamAttempt::where('exam_id', $id)->where('user_id', $user->id)->count();

        if ($exam->max_attempts > 0 && $attemptCount >= $exam->max_attempts) {
            return redirect()->route('student.exams')->with('error', 'Has alcanzado el número máximo de intentos para este examen.');
        }

        // Verificar intento activo
        $activeAttempt = ExamAttempt::where('exam_id', $id)->where('user_id', $user->id)->whereNull('completed_at')->first();

        $questions = $exam->questions()->inRandomOrder()->get();

        if ($activeAttempt) {
            // Calcular tiempo restante
            $startedAt      = $activeAttempt->started_at;
            $examDuration   = $exam->duration * 60;
            $timeElapsed    = now()->diffInSeconds($startedAt);
            $timeRemaining  = max(0, $examDuration - $timeElapsed);

            if ($timeRemaining <= 0) {
                $activeAttempt->update([
                    'completed_at'  => now(),
                    'passed'        => false
                ]);

                return redirect()->route('student.exams.result', $activeAttempt->id)->with('error', 'El tiempo para este intento ha expirado.');
            }

            return view('student.exams.take', [
                'exam'          => $exam,
                'attempt'       => $activeAttempt,
                'questions'     => $questions,
                'timeRemaining' => $timeRemaining,
                'attemptNumber' => $activeAttempt->attempt_number
            ]);
        }

        // Preparar para nuevo intento
        $attemptNumber = $attemptCount + 1;

        return view('student.exams.take', [
            'exam'          => $exam,
            'attempt'       => null,
            'questions'     => $questions,
            'timeRemaining' => $exam->duration * 60,
            'attemptNumber' => $attemptNumber,
            'isNewAttempt'  => true
        ]);
    }

    /**
     * Iniciar un nuevo intento de examen
     */
    public function start(Request $request, $id): RedirectResponse {
        $user = Auth::user();
        $exam = Exam::findOrFail($id);

        // Verificar inscripción
        if (!$user->enrollments()->where('course_id', $exam->course_id)->exists()) {
            return redirect()->route('student.exams')->with('error', 'No estás inscrito en este curso.');
        }

        // Verificar intentos
        $attemptCount = ExamAttempt::where('exam_id', $id)
            ->where('user_id', $user->id)
            ->count();

        if ($exam->max_attempts > 0 && $attemptCount >= $exam->max_attempts) {
            return redirect()->route('student.exams')->with('error', 'Has alcanzado el número máximo de intentos.');
        }

        // Calcular puntos totales
        $totalPoints = $exam->questions()->sum('points');

        // Crear nuevo intento
        $attempt = ExamAttempt::create([
            'exam_id'           => $exam->id,
            'user_id'           => $user->id,
            'attempt_number'    => $attemptCount + 1,
            'started_at'        => now(),
            'answers'           => [],
            'score'             => 0,
            'total_points'      => $totalPoints,
            'passed'            => false
        ]);

        return redirect()->route('student.exams.show', $id);
    }

    /**
     * Guardar respuestas del examen
     */
    public function saveAnswers(Request $request, $id) {
        $request->validate([
            'answers'       => 'required|array',
            'attempt_id'    => 'required|exists:exam_attempts,id'
        ]);

        $attempt = ExamAttempt::where('id', $request->attempt_id)->where('user_id', Auth::id())->whereNull('completed_at')->firstOrFail();

        $attempt->update([
            'answers' => $request->answers
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Finalizar examen
     */
    public function submit(Request $request, $id) {
        // $request->validate([
        //     'answers'       => 'required|array',
        //     'attempt_id'    => 'required|exists:exam_attempts,id'
        // ]);

        // $user       = Auth::user();
        // $exam       = Exam::with('questions')->findOrFail($id);
        // $attempt    = ExamAttempt::where('id', $request->attempt_id)->where('user_id', $user->id)->whereNull('completed_at')->firstOrFail();

        $request->validate([
            'answers'       => 'required|array',
            'attempt_id'    => 'required|exists:exam_attempts,id'
        ]);

        $user       = Auth::user();
        $exam       = Exam::with('questions')->findOrFail($id);
        $attempt    = ExamAttempt::where('id', $request->attempt_id)->where('user_id', $user->id)->whereNull('completed_at')->firstOrFail();

        // Verificar si el tiempo se agotó
        $startedAt = $attempt->started_at;
        $examDuration = $exam->duration * 60;
        $timeElapsed = now()->diffInSeconds($startedAt);

        if ($timeElapsed > $examDuration) {
            // Tiempo agotado - examen fallido
            $attempt->update([
                'completed_at'  => now(),
                'passed'        => false,
                'score'         => 0
            ]);

            return response()->json([
                'success'   => false,
                'message'   => 'El tiempo para este intento ha expirado.',
                'redirect'  => route('student.exams.result', $attempt->id)
            ]);
        }

        // Calcular puntaje
        $score      = 0;
        $answers    = $request->answers;

        foreach ($exam->questions as $question) {
            if (isset($answers[$question->id])) {
                $userAnswer = $answers[$question->id];

                if ($question->type === 'multiple_choice' || $question->type === 'true_false') {
                    if ($userAnswer == $question->correct_answer) {
                        $score += $question->points;
                    }
                }
                // Agregar más tipos de preguntas si es necesario
            }
        }

        // Determinar si pasó
        $passed     = $score >= $exam->passing_score;
        $percentage = $attempt->total_points > 0 ? ($score / $attempt->total_points) * 100 : 0;

        // Actualizar intento
        $attempt->update([
            'answers'       => $answers,
            'score'         => $score,
            'passed'        => $passed,
            'completed_at'  => now()
        ]);

        // return response()->json([
        //     'success'       => true,
        //     'score'         => $score,
        //     'total_points'  => $attempt->total_points,
        //     'percentage'    => round($percentage, 2),
        //     'passed'        => $passed,
        //     'passing_score' => $exam->passing_score,
        //     'redirect'      => route('student.exams.result', $attempt->id)
        // ]);

        // Reemplazar el return response()->json(...) final por:
        return redirect()->route('student.exams.result', $attempt->id);
    }

    /**
     * Ver resultado del examen
     */
    public function result($attemptId): View {
        $attempt    = ExamAttempt::with(['exam.course'])->where('user_id', Auth::id())->findOrFail($attemptId);
        $exam       = $attempt->exam;

        // Calcular estadísticas
        $questions      = $exam->questions()->get();
        $correctCount   = 0;

        if ($attempt->answers && is_array($attempt->answers)) {
            foreach ($questions as $question) {
                if (isset($attempt->answers[$question->id])) {
                    if ($attempt->answers[$question->id] == $question->correct_answer) {
                        $correctCount++;
                    }
                }
            }
        }

        $incorrectCount = $questions->count() - $correctCount;
        $percentage     = $attempt->total_points > 0 ? ($attempt->score / $attempt->total_points) * 100 : 0;

        return view('student.exams.result', compact('attempt', 'exam', 'questions', 'correctCount', 'incorrectCount', 'percentage'));
    }

    /**
     * Ver detalles de un examen realizado
     */
    public function view($attemptId): View {
        $attempt    = ExamAttempt::with(['exam.course'])->where('user_id', Auth::id())->findOrFail($attemptId);
        $exam       = $attempt->exam;
        $questions  = $exam->questions()->get();

        // Calcular estadísticas
        $correctCount = 0;
        if ($attempt->answers && is_array($attempt->answers)) {
            foreach ($questions as $question) {
                if (isset($attempt->answers[$question->id])) {
                    if ($attempt->answers[$question->id] == $question->correct_answer) {
                        $correctCount++;
                    }
                }
            }
        }

        $incorrectCount = $questions->count() - $correctCount;
        $percentage     = $attempt->total_points > 0 ? ($attempt->score / $attempt->total_points) * 100 : 0;

        return view('student.exams.view', compact( 'attempt', 'exam', 'questions', 'correctCount', 'incorrectCount', 'percentage'));
    }

    /**
     * Helper para verificar si puede retomar el examen
     */
    private function canRetakeExam($exam, $userId) {
        if ($exam->max_attempts == 0) {
            return true;
        }

        $attemptCount = ExamAttempt::where('exam_id', $exam->id)->where('user_id', $userId)->count();
        return $attemptCount < $exam->max_attempts;
    }
}
