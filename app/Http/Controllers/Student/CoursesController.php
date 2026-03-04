<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class CoursesController extends Controller {

    public function __construct() {
        $this->middleware(['auth:sanctum', 'student', 'prevent.back']);
    }

    public function dashboard() {
        $user           = Auth::user();
        $enrollments    = Enrollment::with('course.category')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.dashboard', compact('user', 'enrollments'));
    }

    public function myCourses() {
        $user           = Auth::user();
        $enrollments    = Enrollment::with(['course.category', 'course.sections.lessons'])
            ->where('user_id', $user->id)
            ->whereHas('course', function($query) {
                $query->where('type', 'course');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Preparar los datos para la vista
        $coursesData        = $enrollments->map(function($enrollment) {
            $course         = $enrollment->course;
            $progress       = $enrollment->progress ?: 0;
            $totalLessons   = 0;

            // Calcular total de lecciones
            if ($course->sections) {
                $totalLessons = $course->sections->sum(function($section) {
                    return $section->lessons ? $section->lessons->count() : 0;
                });
            }

            return [
                'id'                => $enrollment->id,
                'course_id'         => $course->id,
                'title'             => $course->title,
                'description'       => $course->description,
                'category'          => $course->category ? $course->category->name : 'Sin categoría',
                'image'             => $course->image_url ?: null,
                'progress'          => $progress,
                'status'            => $progress >= 100 ? 'completed' : 'in_progress',
                'modules'           => $course->sections ? $course->sections->count() : 0,
                'lessons'           => $totalLessons,
                'duration'          => $course->duration ?: '0 horas',
                'enrolled_date'     => $enrollment->created_at->format('d/m/Y'),
                'last_accessed'     => $enrollment->last_accessed_at ? $enrollment->last_accessed_at->format('d/m/Y H:i') : null,
                'completed_lessons' => $enrollment->completed_lessons_count ?: 0,
                'total_lessons'     => $totalLessons,
                'continue_url'      => route('student.course.learn', $course)
            ];
        });

        return view('student.my-courses', compact('enrollments', 'coursesData'));
    }

    public function learn(Course $course): View {
        return view('student.courses.learn', compact('course'));
    }
}
