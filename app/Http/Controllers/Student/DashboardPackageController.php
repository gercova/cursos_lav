<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Certificate;
use App\Models\ExamAttempt;
use App\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardPackageController extends Controller {

    public function __construct(){
        $this->middleware(['auth:sanctum', 'student', 'prevent.back']);
    }

    // public function index(): View {
    //     $user = Auth::user();

    //     // 1. Filtrar los usuarios (colaboradores) por parent_id o company_code
    //     $employeeIds = User::where(function ($query) use ($user) {
    //         $query->where('parent_id', $user->id);
    //         if (!empty($user->company_code)) {
    //             $query->orWhere('company_code', $user->company_code);
    //         }
    //     })
    //     ->where('id', '!=', $user->id) // Excluir al propio administrador
    //     ->pluck('id');

    //     // 2. Indicadores Principales (Métricas)
    //     $totalEmployees    = $employeeIds->count();
    //     $totalCertificates = Certificate::whereIn('user_id', $employeeIds)->count();
    //     $totalExams        = ExamAttempt::whereIn('user_id', $employeeIds)->count();

    //     // 3. Progreso de los usuarios por curso
    //     $userProgress = Enrollment::with(['user', 'course'])
    //         ->whereIn('user_id', $employeeIds)
    //         ->orderBy('progress', 'desc')
    //         ->get();

    //     // 4. Actividades top 10 de los usuarios
    //     $topActivities = UserActivity::with('user')
    //         ->whereIn('user_id', $employeeIds)
    //         ->latest()
    //         ->take(10)
    //         ->get();

    //     // 5. Data para los reportes gráficos (Chart.js)
    //     $enrollmentStats = [
    //         'completed'   => Enrollment::whereIn('user_id', $employeeIds)->where('progress', 100)->count(),
    //         'in_progress' => Enrollment::whereIn('user_id', $employeeIds)->where('progress', '<', 100)->count(),
    //     ];

    //     $examStats = [
    //         'passed' => ExamAttempt::whereIn('user_id', $employeeIds)->where('passed', true)->count(),
    //         'failed' => ExamAttempt::whereIn('user_id', $employeeIds)->where('passed', false)->count(),
    //     ];

    //     return view('student.company.dashboard', compact(
    //         'totalEmployees',
    //         'totalCertificates',
    //         'totalExams',
    //         'userProgress',
    //         'topActivities',
    //         'enrollmentStats',
    //         'examStats'
    //     ));
    // }

    public function index(): View {
        $user = Auth::user();

        // 1. Filtrar los usuarios (colaboradores) por parent_id o company_code
        $employeeIds = User::where(function ($query) use ($user) {
            $query->where('parent_id', $user->id);
            if (!empty($user->company_code)) {
                $query->orWhere('company_code', $user->company_code);
            }
        })
        ->where('id', '!=', $user->id)
        ->pluck('id');

        // 2. Indicadores Principales (Métricas)
        $totalEmployees    = $employeeIds->count();
        $totalCertificates = Certificate::whereIn('user_id', $employeeIds)->count();
        $totalExams        = ExamAttempt::whereIn('user_id', $employeeIds)->count();

        // 3. Progreso de los usuarios por curso (Tabla 1)
        $userProgress = Enrollment::with(['user', 'course'])
            ->whereIn('user_id', $employeeIds)
            ->orderBy('progress', 'desc')
            ->get();

        // 4. Actividades top 10 de los usuarios (Línea de tiempo)
        $topActivities = UserActivity::with('user')
            ->whereIn('user_id', $employeeIds)
            ->latest()
            ->take(10)
            ->get();

        // 5. Promedio de progreso general por curso (Tabla 2)
        $courseAverages = Enrollment::with('course')
            ->whereIn('user_id', $employeeIds)
            ->get()
            ->groupBy('course_id')
            ->map(function ($enrollments) {
                return [
                    'course' => $enrollments->first()->course->title ?? 'N/A',
                    'avg_progress' => round($enrollments->avg('progress'), 1),
                    'total_students' => $enrollments->count()
                ];
            })->sortByDesc('total_students');

        // --- DATA PARA GRÁFICOS (CHART.JS) ---
        
        // A. Estado de inscripciones
        $enrollmentStats = [
            'completed'   => Enrollment::whereIn('user_id', $employeeIds)->where('progress', 100)->count(),
            'in_progress' => Enrollment::whereIn('user_id', $employeeIds)->where('progress', '<', 100)->count(),
        ];

        // B. Exámenes
        $examStats = [
            'passed' => ExamAttempt::whereIn('user_id', $employeeIds)->where('passed', true)->count(),
            'failed' => ExamAttempt::whereIn('user_id', $employeeIds)->where('passed', false)->count(),
        ];

        // C. Cursos más populares (Top 5)
        $popularCoursesQuery = $courseAverages->take(5);
        $popularCoursesStats = [
            'labels' => $popularCoursesQuery->pluck('course')->toArray(),
            'data'   => $popularCoursesQuery->pluck('total_students')->toArray(),
        ];

        // D. Certificados obtenidos en los últimos 6 meses
        $certMonths = [];
        $certCounts = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $certMonths[] = ucfirst($date->translatedFormat('M y')); // ej: Mar 26
            
            $certCounts[] = Certificate::whereIn('user_id', $employeeIds)
                ->whereYear('issue_date', $date->year)
                ->whereMonth('issue_date', $date->month)
                ->count();
        }
        $certificateStats = [
            'labels' => $certMonths,
            'data'   => $certCounts,
        ];

        return view('student.company.dashboard', compact(
            'totalEmployees',
            'totalCertificates',
            'totalExams',
            'userProgress',
            'topActivities',
            'courseAverages',
            'enrollmentStats',
            'examStats',
            'popularCoursesStats',
            'certificateStats'
        ));
    }
}