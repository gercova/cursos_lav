<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CompletedLessons;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller {

    public function __construct() {
        $this->middleware(['auth:sanctum', 'student', 'prevent.back']);
    }

    // public function show($courseSlug, $lessonId) {
    //     $user   = Auth::user();
    //     $course = Course::where('slug', $courseSlug)->where('is_active', true)->with(['sections.lessons', 'instructor', 'documents'])->firstOrFail();
    //     $lesson = Lesson::where('id', $lessonId)->where('is_active', true)->with('section')->firstOrFail();
    //     // Verificar que el usuario esté inscrito en el curso
    //     $enrollment = Enrollment::where('user_id', $user->id)->where('course_id', $course->id)->firstOrFail();
    //     // Registrar último acceso
    //     $enrollment->update(['last_accessed_at' => now()]);

    //     return view('student.courses.lesson', compact('course', 'lesson', 'enrollment'));
    // }

    public function show($courseSlug, $lessonId): View {
        $user   = Auth::user();
        $course = Course::where('slug', $courseSlug)
            ->where('is_active', true)
            ->with(['sections.lessons', 'instructor', 'documents'])
            ->firstOrFail();

        $lesson = Lesson::where('id', $lessonId)
            ->where('is_active', true)
            ->with('section')
            ->with('vimeo')
            ->firstOrFail();

        // Verificar que el usuario esté inscrito en el curso
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        // Obtener el progreso de esta lección específica
        $lessonProgress = LessonProgress::where('enrollment_id', $enrollment->id)
            ->where('lesson_id', $lessonId)
            ->where('user_id', $user->id)
            ->first();

        // Determinar si la lección está completada
        $isCompleted = CompletedLessons::where('enrollment_id', $enrollment->id)
            ->where('lesson_id', $lessonId)
            ->exists();

        // Obtener porcentaje visto
        $watchedPercent = $lessonProgress ? $lessonProgress->progress : 0;

        // Registrar último acceso
        $enrollment->update(['last_accessed_at' => now()]);

        return view('student.courses.lesson', compact('course', 'lesson', 'enrollment', 'lessonProgress', 'isCompleted', 'watchedPercent'));
    }

    public function saveProgress(Request $request): JsonResponse {
        set_time_limit(0);
        
        $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'lesson_id'     => 'required|exists:lessons,id',
            'progress'      => 'required|numeric|min:0|max:100',
            'time_watched'  => 'required|integer|min:0'
        ]);

        $enrollment = Enrollment::findOrFail($request->enrollment_id);

        // Verificar que el enrollment pertenezca al usuario autenticado
        if ($enrollment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Guardar o actualizar progreso
        $lessonProgress = LessonProgress::updateOrCreate(
            [
                'enrollment_id' => $request->enrollment_id,
                'lesson_id'     => $request->lesson_id,
                'user_id'       => Auth::id()
            ],
            [
                'progress'      => $request->progress,
                'time_watched'  => $request->time_watched,
                'completed'     => $request->progress >= 80
            ]
        );

        // Actualizar progreso general del enrollment
        $this->updateEnrollmentProgress($enrollment);

        return response()->json([
            'success'   => true,
            'progress'  => $lessonProgress->progress
        ]);
    }

    public function complete(Request $request): JsonResponse {
        $request->validate([
            'enrollment_id'         => 'required|exists:enrollments,id',
            'lesson_id'             => 'required|exists:lessons,id',
            'time_spent_minutes'    => 'required|integer|min:1'
        ]);

        $enrollment = Enrollment::findOrFail($request->enrollment_id);

        // Verificar que el enrollment pertenezca al usuario autenticado
        if ($enrollment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Marcar lección como completada
        CompletedLessons::updateOrCreate(
            [
                'enrollment_id' => $request->enrollment_id,
                'lesson_id'     => $request->lesson_id
            ],
            [
                'completed_at'          => now(),
                'time_spent_minutes'    => $request->time_spent_minutes
            ]
        );

        // Actualizar progreso de la lección
        LessonProgress::updateOrCreate(
            [
                'enrollment_id' => $request->enrollment_id,
                'lesson_id'     => $request->lesson_id,
                'user_id'       => Auth::id()
            ],
            [
                'progress'      => 100,
                'completed'     => true,
                'completed_at'  => now(),
                'time_watched'  => $request->time_spent_minutes * 60
            ]
        );

        // Actualizar progreso general del enrollment
        $this->updateEnrollmentProgress($enrollment);

        return response()->json([
            'success' => true,
            'message' => 'Lección marcada como completada'
        ]);
    }

    private function updateEnrollmentProgress(Enrollment $enrollment) {
        $course         = $enrollment->course;
        $totalLessons   = $course->sections->sum(function($section) {
            return $section->lessons->count();
        });

        $completedLessons = $enrollment->completedLessons->count();

        if ($totalLessons > 0) {
            $progress = ($completedLessons / $totalLessons) * 100;
            $enrollment->update([
                'progress'      => $progress,
                'status'        => $progress >= 100 ? 'completed' : 'in_progress',
                'completed_at'  => $progress >= 100 ? now() : null
            ]);
        }
    }
}
