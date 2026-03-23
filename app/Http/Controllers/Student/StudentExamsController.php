<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\ExamQuestion;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StudentExamsController extends Controller {

    public  function __construct() {
        $this->middleware(['auth', 'student', 'prevent.back']);
    }
    
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

        // Obtener TODOS los intentos del estudiante (completados y activos)
        $attempts = ExamAttempt::where('user_id', $user->id)
            ->with('exam')
            ->get()
            ->groupBy('exam_id');

        // Separar exámenes
        $pendingExams   = collect();
        $completedExams = collect();

        foreach ($enrolledCourses as $exam) {
            $examAttempts = $attempts->get($exam->id) ?? collect();

            // Contar intentos totales para este examen
            $totalAttempts = $examAttempts->count();

            // Buscar intentos completados
            $completedAttempts = $examAttempts->filter(function($attempt) {
                return $attempt->completed_at !== null;
            });

            // Buscar intento activo (sin completar)
            $activeAttempt = $examAttempts->first(function($attempt) {
                return $attempt->completed_at === null;
            });

            if ($completedAttempts->count() > 0) {
                // Examen con intentos completados
                $exam->attempts = $completedAttempts->sortByDesc('attempt_number');
                $exam->last_attempt = $completedAttempts->sortByDesc('attempt_number')->first();
                $exam->attempt_count = $totalAttempts;
                $exam->can_retake = $this->canRetakeExam($exam, $user->id);
                $completedExams->push($exam);
            } elseif ($activeAttempt) {
                // Tiene un intento activo (sin completar)
                $exam->active_attempt = $activeAttempt;
                $exam->attempt_count = $totalAttempts;
                $pendingExams->push($exam);
            } else {
                // Examen sin intentos
                $exam->attempt_count = 0;
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
            return redirect()->route('student.exams')->with('error', 'No estás inscrito en este curso.');
        }

        // Obtener todos los intentos (completados e incompletos)
        $allAttempts = ExamAttempt::where('exam_id', $id)
            ->where('user_id', $user->id)
            ->get();

        // Contar intentos completados
        $completedAttemptsCount = $allAttempts->whereNotNull('completed_at')->count();

        // Verificar intentos máximos permitidos
        if ($exam->max_attempts > 0 && $completedAttemptsCount >= $exam->max_attempts) {
            return redirect()->route('student.exams')->with('error', 'Has alcanzado el número máximo de intentos para este examen.');
        }

        $activeAttempt = ExamAttempt::where('exam_id', $id)
            ->where('user_id', $user->id)
            ->whereNull('completed_at')
            ->first();

        if ($activeAttempt && $activeAttempt->isExpired()) {
            // Marcar como expirado automáticamente
            $activeAttempt->update([
                'completed_at'  => now(),
                'passed'        => false,
                'score'         => 0
            ]);

            return redirect()->route('student.exams.result', $activeAttempt->id)
                ->with('error', 'El tiempo para tu intento anterior había expirado.');
        }

        $questions = $exam->questions()->inRandomOrder()->get();

        // Contar todos los intentos (para mostrar el número de intento actual)
        $totalAttemptsCount = $allAttempts->count();

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
                'exam'              => $exam,
                'attempt'           => $activeAttempt,
                'questions'         => $questions,
                'timeRemaining'     => $timeRemaining,
                'attemptNumber'     => $activeAttempt->attempt_number,
                'numberAttempts'    => $totalAttemptsCount,
            ]);
        }

        // Preparar para nuevo intento
        // El número de intento debe ser mayor que el último intent_number
        $lastAttemptNumber  = $allAttempts->max('attempt_number') ?? 0;
        $attemptNumber      = $lastAttemptNumber + 1;

        return view('student.exams.take', [
            'exam'          => $exam,
            'attempt'       => null,
            'questions'     => $questions,
            'timeRemaining' => $exam->duration * 60,
            'attemptNumber' => $attemptNumber,
            'numberAttempts' => $totalAttemptsCount,
            'isNewAttempt'  => true
        ]);
    }

    /**
     * Iniciar un nuevo intento de examen
     */
    public function start(Request $request, $id): RedirectResponse {
        $user = Auth::user();
        $exam = Exam::findOrFail($id);

        // dd(!$user->enrollments()->where('course_id', $exam->course_id)->exists());

        // Verificar inscripción
        if (!$user->enrollments()->where('course_id', $exam->course_id)->exists()) {
            return redirect()->route('student.exams')->with('error', 'No estás inscrito en este curso.');
        }

        // Obtener todos los intentos previos
        $allAttempts = ExamAttempt::where('exam_id', $id)
            ->where('user_id', $user->id)
            ->get();

        // Contar intentos completados
        $completedAttemptsCount = $allAttempts->whereNotNull('completed_at')->count();

        // Verificar intentos máximos
        if ($exam->max_attempts > 0 && $completedAttemptsCount >= $exam->max_attempts) {
            return redirect()->route('student.exams')->with('error', 'Has alcanzado el número máximo de intentos permitidos.');
        }

        // Verificar si ya hay un intento activo
        $activeAttempt = $allAttempts->whereNull('completed_at')->first();
        if ($activeAttempt) {
            return redirect()->route('student.exams.show', $id);
        }

        // Calcular puntos totales
        $totalPoints = $exam->questions()->sum('points');

        // Calcular número de intento (mayor que el último)
        $lastAttemptNumber = $allAttempts->max('attempt_number') ?? 0;
        $attemptNumber = $lastAttemptNumber + 1;

        // Crear nuevo intento
        $attempt = ExamAttempt::create([
            'exam_id'           => $exam->id,
            'user_id'           => $user->id,
            'attempt_number'    => $attemptNumber,
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
    public function saveAnswers(Request $request, $id): JsonResponse {
        try {
            // Validar manualmente para mejor debugging
            if (!$request->has('answers') || !is_array($request->answers)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El campo answers es requerido y debe ser un array'
                ], 422);
            }

            if (!$request->has('attempt_id')) {
                return response()->json([
                    'success' => false,
                    'message' => 'El campo attempt_id es requerido'
                ], 422);
            }

            $userId     = Auth::id();
            $attempt    = ExamAttempt::where('id', $request->attempt_id)->where('user_id', $userId)->whereNull('completed_at')->first();

            if (!$attempt) {
                return response()->json([
                    'success'   => false,
                    'message'   => 'Intento no encontrado, ya completado o no autorizado'
                ], 200);
            }

            // Verificar que el intento pertenece al examen correcto
            if ($attempt->exam_id != $id) {
                return response()->json([
                    'success'   => false,
                    'message'   => 'El intento no corresponde a este examen'
                ], 422);
            }

            // Validar que las respuestas correspondan a preguntas del examen
            $exam = Exam::with('questions')->findOrFail($id);
            $validatedAnswers = [];

            foreach ($request->answers as $questionId => $answer) {
                // Verificar que la pregunta existe en este examen
                $questionExists = $exam->questions->contains('id', $questionId);

                if ($questionExists) {
                    // Sanitizar la respuesta
                    if (is_numeric($answer)) {
                        $validatedAnswers[$questionId] = (string) $answer;
                    } else {
                        $validatedAnswers[$questionId] = $answer;
                    }
                }
            }

            // Log para debugging
            Log::info('Guardando respuestas', [
                'user_id'           => $userId,
                'exam_id'           => $id,
                'attempt_id'        => $request->attempt_id,
                'answers_count'     => count($validatedAnswers),
                'answers_sample'    => array_slice($validatedAnswers, 0, 3)
            ]);

            $attempt->update([
                'answers'       => $validatedAnswers,
                'updated_at'    => now()
            ]);

            return response()->json([
                'success'       => true,
                'message'       => 'Progreso guardado',
                'saved_at'      => now()->format('H:i:s'),
                'answers_count' => count($validatedAnswers)
            ]);

        } catch (\Exception $e) {
            Log::error('Error al guardar respuestas: ' . $e->getMessage(), [
                'user_id'           => Auth::id(),
                'exam_id'           => $id,
                'attempt_id'        => $request->attempt_id ?? 'no-provided',
                'answers_received'  => $request->answers ?? 'no-answers'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno al guardar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Finalizar examen
     */
    public function submit(Request $request, $id): JsonResponse|RedirectResponse {
        $request->validate([
            'answers'       => 'required|array',
            'attempt_id'    => 'required|exists:exam_attempts,id'
        ]);

        $user   = Auth::user();
        $exam   = Exam::with('questions')->findOrFail($id);

        // Buscar intento activo (sin completar) del usuario
        $attempt = ExamAttempt::where('id', $request->attempt_id)
            ->where('user_id', $user->id)
            ->whereNull('completed_at')
            ->first();

        if (!$attempt) {
            // Verificar si ya fue completado (doble envío / refresco)
            $completedAttempt = ExamAttempt::where('id', $request->attempt_id)
                ->where('user_id', $user->id)
                ->whereNotNull('completed_at')
                ->first();

            if ($completedAttempt) {
                return response()->json([
                    'success'   => false,
                    'message'   => 'Este intento ya fue completado.',
                    'redirect'  => route('student.exams.result', $completedAttempt->id)
                ], 422);
            }

            return response()->json([
                'success'   => false,
                'message'   => 'Intento no encontrado o no autorizado.'
            ], 200);
        }

        // Verificar si el tiempo se agotó
        $startedAt      = $attempt->started_at;
        $examDuration   = $exam->duration * 60;
        $timeElapsed    = now()->diffInSeconds($startedAt);

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

        // Log para debugging
        Log::info('Calculando puntaje del examen', [
            'exam_id'           => $id,
            'attempt_id'        => $request->attempt_id,
            'questions_count'   => $exam->questions->count(),
            'answers_received'  => $request->answers
        ]);

        // Calcular puntaje - MÉTODO MEJORADO
        $score          = 0;
        $answers        = $request->answers;
        $correctCount   = 0;
        $totalQuestions = $exam->questions->count();

        foreach ($exam->questions as $question) {
            if (isset($answers[$question->id])) {
                $userAnswer = $answers[$question->id];

                // Convertir tipos para comparación consistente
                if (is_numeric($userAnswer)) {
                    $userAnswer = (string) $userAnswer;
                }

                if (is_numeric($question->correct_answer)) {
                    $correctAnswer = (string) $question->correct_answer;
                } else {
                    $correctAnswer = $question->correct_answer;
                }

                Log::debug('Validando respuesta', [
                    'question_id'       => $question->id,
                    'user_answer'       => $userAnswer,
                    'correct_answer'    => $correctAnswer,
                    'type_user'         => gettype($userAnswer),
                    'type_correct'      => gettype($correctAnswer),
                    'comparison'        => $userAnswer == $correctAnswer ? 'true' : 'false'
                ]);

                if ($question->type === 'multiple_choice' || $question->type === 'true_false') {
                    // Comparación estricta pero con tipos convertidos
                    if ($userAnswer == $correctAnswer) {
                        $score += $question->points;
                        $correctCount++;
                    }
                }
                // Agregar más tipos de preguntas si es necesario
            }
        }

        // Puntos mínimos para aprobar
        $minimumPointsToPass        = round((($attempt->total_points * (int) $exam->passing_score)/100), 2);
        $percentageOfPointsEarned   = round((($score * 100)/$attempt->total_points), 2);

        if($score > 0 && $minimumPointsToPass <= $score){
            $passed = true; 
        } else {
            $passed = false;
        }

        // Log del resultado
        Log::info('Resultado del examen calculado', [
            'exam_id'           => $id,
            'attempt_id'        => $request->attempt_id,
            'score'             => $score,
            'total_points'      => $attempt->total_points,
            'correct_count'     => $correctCount,
            'total_questions'   => $totalQuestions,
            'percentage'        => $percentageOfPointsEarned,
            'passed'            => $passed,
            'passing_score'     => $exam->passing_score
        ]);

        // Actualizar intento
        $attempt->update([
            'answers'       => $answers,
            'score'         => $score,
            'passed'        => $passed,
            'completed_at'  => now()
        ]);

        $existsCertificate = Certificate::join('exam_attempts as et', 'certificates.exam_attempt_id', '=', 'et.id')
            ->where('certificates.user_id', $attempt->user_id)
            ->where('certificates.course_id', $exam->course_id)
            ->where('certificates.exam_attempt_id', $request->attempt_id)
            ->where('et.passed', true)
            ->exists();

        if(!$existsCertificate) {
            $createCertificate = Certificate::create([
                'user_id'               => $attempt->user_id,
                'course_id'             => $exam->course_id,
                'exam_attempt_id'       => $attempt->id,
                'certificate_code'      => $this->createCertificateCode($attempt->user_id),
                'certificate_number'    => $this->createCertificateNumber($attempt->user_id),
                'issue_date'            => now()->format('Y-m-d H:i:s'),
                'expiry_date'           => null,
                'total_hours'           => $exam->course->duration,
                'download_count'        => 0,
            ]);
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Examen completado',
            'redirect'  => route('student.exams.result', $attempt->id)
        ]);
    }

    public function createCertificateCode($userId): string {
        $certificateUser = Certificate::where('user_id', $userId)->count();

        $countDigit = strlen((string) $userId);
        if($countDigit == 1) {
            $fill = '000';
        } elseif($countDigit == 2) {
            $fill = '00';
        } elseif ($countDigit == 3) {
            $fill = '0';
        } else {
            $fill = '';
        }

        ($certificateUser > 0) ? $certificateUser += 1 : $certificateUser = 1;

        $countCertificates = strlen((string) $certificateUser);
        if ($countCertificates == 1) {
            $fill2 = '000';
        } elseif ($countCertificates == 2) {
            $fill2 = '00';
        } elseif ($countCertificates == 3) {
            $fill2 = '0';
        }

        $codeCertificate = 'CERT-'. $fill2.$certificateUser . '-' . $fill.$userId;
        return strtoupper($codeCertificate);
    }

    public function createCertificateNumber($userId): String {

        $countDigit = strlen((string) $userId);
        if($countDigit == 1) {
            $fill = '000';
        } elseif($countDigit == 2) {
            $fill = '00';
        } elseif ($countDigit == 3) {
            $fill = '0';
        } else {
            $fill = '';
        }

        $course         = Enrollment::where('user_id', $userId)->first();
        $courseId       = $course->course_id;
        $catCourseId    = $course->course->category_id;

        $countDigitCourse = strlen((string) $courseId);
        if($countDigitCourse == 1) {
            $fill2 = '000';
        } elseif($countDigitCourse == 2) {
            $fill2 = '00';
        } elseif ($countDigitCourse == 3) {
            $fill2 = '0';
        } else {
            $fill2 = '';
        }

        $countDigitCat = strlen((string) $catCourseId);
        if($countDigitCat == 1) {
            $fill3 = '000';
        } elseif($countDigitCat == 2) {
            $fill3 = '00';
        } elseif ($countDigitCat == 3) {
            $fill3 = '0';
        } else {
            $fill3 = '';
        }

        $certificateNumber = $fill.$userId . $fill3.$catCourseId . $fill2. $courseId. 'IPF-EDUCA';
        return strtoupper($certificateNumber);
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
        $certificate = Certificate::where('exam_attempt_id', $attemptId)->first();
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

        return view('student.exams.view', compact( 'attempt', 'exam', 'questions', 'correctCount', 'incorrectCount', 'percentage', 'certificate'));
    }

    /**
     * Helper para verificar si puede retomar el examen
     */
    private function canRetakeExam($exam, $userId) {
        if ($exam->max_attempts == 0) {
            return true; // Intentos ilimitados
        }

        // Contar solo intentos completados
        $completedAttemptsCount = ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->count();

        // Puede reintentar si los intentos completados son menores al máximo
        return $completedAttemptsCount < $exam->max_attempts;
    }
}