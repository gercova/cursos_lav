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
        // Filtrar por nivel
        if ($request->has('level') && $request->level) {
            $query->where('level', $request->level);
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

        // Filtrar por nivel
        if ($request->has('level') && $request->level) {
            $query->where('level', $request->level);
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

        return view('student.my-courses', compact('enrollments'));
    }
}
