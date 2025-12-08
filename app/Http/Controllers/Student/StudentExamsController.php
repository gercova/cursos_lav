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
        $this->middleware(['auth', 'student']);
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
            return redirect()->back()
                ->with('error', 'Has alcanzado el número máximo de intentos para este examen.');
        }

        // Crear nuevo intento
        $attempt = ExamAttempt::create([
            'exam_id' => $exam->id,
            'user_id' => Auth::id(),
            'attempt_number' => $attemptCount + 1,
            'started_at' => now(),
            'answers' => [],
        ]);

        return redirect()->route('exam.take', $attempt->id);
    }

    public function take($attemptId) {
        $attempt = ExamAttempt::with('exam.questions')
            ->where('user_id', Auth::id())
            ->findOrFail($attemptId);

        if ($attempt->completed_at) {
            return redirect()->route('exam.result', $attempt->id);
        }

        if ($attempt->isExpired()) {
            $attempt->update([
                'completed_at' => now(),
                'score' => 0,
                'passed' => false,
            ]);

            return redirect()->route('exam.result', $attempt->id);
        }

        $questions = $attempt->exam->getRandomQuestions();

        return view('student.exam.take', compact('attempt', 'questions'));
    }

    public function submit($courseId, Request $request) {
        $attempt = ExamAttempt::with('exam')
            ->where('user_id', Auth::id())
            ->findOrFail($request->attempt_id);

        if ($attempt->completed_at) {
            return redirect()->route('exam.result', $attempt->id);
        }

        $answers    = $request->answers ?? [];
        $score      = 0;
        $totalPoints = 0;

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
        $passed     = $finalScore >= $attempt->exam->passing_score;

        $attempt->update([
            'completed_at'  => now(),
            'score'         => $finalScore,
            'passed'        => $passed,
            'answers'       => $answers,
        ]);

        // Generar certificado si aprobó
        if ($passed) {
            Certificate::create([
                'user_id'           => Auth::id(),
                'course_id'         => $courseId,
                'exam_attempt_id'   => $attempt->id,
                'certificate_code'  => Certificate::generateVerificationCode(),
                'issue_date'        => now(),
                'expiry_date'       => now()->addYears(2),
                'download_count'    => 0,
            ]);
        }

        return redirect()->route('exam.result', $attempt->id);
    }

    public function result($attemptId) {
        $attempt = ExamAttempt::with(['exam', 'certificate'])
            ->where('user_id', Auth::id())
            ->findOrFail($attemptId);

        return view('student.exam.result', compact('attempt'));
    }
}
