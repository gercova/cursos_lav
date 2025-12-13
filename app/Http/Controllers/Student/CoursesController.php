<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CoursesController extends Controller {

    public function index(Request $request): View {
        $query = Course::with(['instructor'])->where('is_active', true);
        // Filtrar por categoría
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        $courses    = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();
        return view('student.home', compact('courses', 'categories'));
    }

    public function courses(Request $request) {
        $query = Course::with(['category', 'instructor'])->where('is_active', true);
        // Filtro por búsqueda
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%')
                    ->orWhere('short_description', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('category', function($q) use ($searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('instructor', function($q) use ($searchTerm) {
                        $q->where('names', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        // Filtrar por categoría
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Ordenar
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'popular':
                $query->withCount('enrollments')->orderBy('enrollments_count', 'desc');
                break;
            case 'rating':
                // Asumiendo que tienes un campo de rating o reviews
                $query->orderBy('rating', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('title', 'desc');
                break;
            default: // newest
                $query->orderBy('created_at', 'desc');
        }

        $courses = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        // Si es una petición AJAX, retornar solo la vista parcial
        if ($request->ajax()) {
            return view('student.partials.courses-grid', compact('courses'))->render();
        }

        return view('student.courses', compact('courses', 'categories'));
    }

    public function show($id): View {
        $course = Course::with(['sections.lessons', 'category', 'instructor', 'documents'])
            ->where('is_active', true)
            ->findOrFail($id);

        $isEnrolled = false;
        if (Auth::check()) {
            $isEnrolled = Enrollment::where('user_id', Auth::id())->where('course_id', $id)->exists();
        }

        return view('student.course-detail', compact('course', 'isEnrolled'));
    }

    public function dashboard() {
        $user = Auth::user();
        $enrollments = Enrollment::with('course.category')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.dashboard', compact('user', 'enrollments'));
    }

    public function myCourses() {
        $user = Auth::user();
        $enrollments = Enrollment::with(['course.category', 'course.sections.lessons'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Preparar los datos para la vista
        $coursesData = $enrollments->map(function($enrollment) {
            $course = $enrollment->course;
            $progress = $enrollment->progress ?: 0;
            $totalLessons = 0;

            // Calcular total de lecciones
            if ($course->sections) {
                $totalLessons = $course->sections->sum(function($section) {
                    return $section->lessons ? $section->lessons->count() : 0;
                });
            }

            return [
                'id'            => $enrollment->id,
                'course_id'     => $course->id,
                'title'         => $course->title,
                'description'   => $course->description,
                'category'      => $course->category ? $course->category->name : 'Sin categoría',
                'image'         => $course->image_url ?: null,
                'progress'      => $progress,
                'status'        => $progress >= 100 ? 'completed' : 'in_progress',
                'modules'       => $course->sections ? $course->sections->count() : 0,
                'lessons'       => $totalLessons,
                'duration'      => $course->duration ?: '0 horas',
                'enrolled_date' => $enrollment->created_at->format('d/m/Y'),
                'last_accessed' => $enrollment->last_accessed_at ? $enrollment->last_accessed_at->format('d/m/Y H:i') : null,
                'completed_lessons' => $enrollment->completed_lessons_count ?: 0,
                'total_lessons' => $totalLessons,
                'continue_url'  => route('course.learn', $course->slug)
            ];
        });

        return view('student.my-courses', compact('enrollments', 'coursesData'));
    }
}
