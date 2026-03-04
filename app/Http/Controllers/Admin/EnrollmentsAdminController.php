<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EnrollmentsAdminController extends Controller {

    public function __construct() {
        $this->middleware(['auth:sanctum', 'admin', 'prevent.back']);
    }

    public function index(Request $request): View {
        $query = Enrollment::with(['user', 'course.category'])
            ->whereHas('user') // Solo inscripciones con usuario existente
            ->whereHas('course') // Solo inscripciones con curso existente
            ->latest();

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('names', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('course', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('course')) {
            $query->where('course_id', $request->course);
        }

        $enrollments    = $query->paginate(10);
        $courses        = Course::where('is_active', true)->get();

        $stats = [
            'total'     => Enrollment::whereHas('user')->whereHas('course')->count(),
            'active'    => Enrollment::where('status', 'active')->whereHas('user')->whereHas('course')->count(),
            'completed' => Enrollment::where('status', 'completed')->whereHas('user')->whereHas('course')->count(),
            'cancelled' => Enrollment::where('status', 'cancelled')->whereHas('user')->whereHas('course')->count(),
        ];

        return view('admin.enrollments.index', compact('enrollments', 'courses', 'stats'));
    }

    public function enrollmentShow(Enrollment $enrollment): View {
        $enrollment->load([
            'user',
            'course.category',
            'course.instructor',
            'payments'
        ]);

        return view('admin.enrollments.show', compact('enrollment'));
    }

    public function updateEnrollmentStatus(Request $request, Enrollment $enrollment): JsonResponse {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['active', 'completed', 'cancelled', 'pending'])]
        ]);

        $enrollment->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Estado de la inscripción actualizado.'
        ]);
    }
}
