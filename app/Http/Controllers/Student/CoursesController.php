<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoursesController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with('category')
            ->where('is_active', true);

        // Filtrar por categoría
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filtrar por búsqueda
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Ordenar
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'popular':
                $query->withCount('enrollments')->orderBy('enrollments_count', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $courses = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('student.home', compact('courses', 'categories'));
    }

    public function show($id)
    {
        $course = Course::with(['sections.lessons', 'category', 'instructor', 'documents'])
            ->where('is_active', true)
            ->findOrFail($id);

        $isEnrolled = false;
        if (Auth::check()) {
            $isEnrolled = Enrollment::where('user_id', Auth::id())
                ->where('course_id', $id)
                ->exists();
        }

        return view('student.course-detail', compact('course', 'isEnrolled'));
    }

    public function dashboard()
    {
        $user = Auth::user();
        $enrollments = Enrollment::with('course.category')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.dashboard', compact('user', 'enrollments'));
    }

    public function myCourses()
    {
        $user = Auth::user();
        $enrollments = Enrollment::with(['course.category', 'course.sections.lessons'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.my-courses', compact('enrollments'));
    }
}
