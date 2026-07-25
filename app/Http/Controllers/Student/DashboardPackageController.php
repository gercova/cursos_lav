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

    public function index(): View {
        $user = Auth::user();

        // 1. Obtener IDs de colaboradores vinculados
        $employeeIds = User::where(function ($query) use ($user) {
            $query->where('parent_id', Auth::id());
        })
        ->where('id', '!=', $user->id)
        ->pluck('id');

        // 2. Métricas Generales para Cards
        $totalEmployees = $employeeIds->count();
        $totalCertificates = Certificate::whereIn('user_id', $employeeIds)->count();
        $totalExams = ExamAttempt::whereIn('user_id', $employeeIds)->count();

        // Card: Capacitados (Al menos 1 curso completado al 100%)
        $totalTrained = User::whereIn('id', $employeeIds)
            ->whereHas('enrollments', fn($q) => $q->where('progress', 100))
            ->count();

        // Card: % Progreso General de la Empresa
        $overallProgressAvg = Enrollment::whereIn('user_id', $employeeIds)->avg('progress') ?? 0;
        $overallProgressAvg = round($overallProgressAvg, 1);

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

        // 5. Progreso Total por Colaborador (Para la nueva tabla)
        $collaboratorProgress = User::whereIn('id', $employeeIds)
            ->with('enrollments')
            ->get()
            ->map(function ($u) {
                $enrollments = $u->enrollments;
                $avg = $enrollments->count() > 0 ? $enrollments->avg('progress') : 0;
                return [
                    'name' => $u->names,
                    'profession' => $u->profession ?: 'Colaborador',
                    'photo' => $u->profile_photo_url,
                    'total_courses' => $enrollments->count(),
                    'completed' => $enrollments->where('progress', 100)->count(),
                    'avg_progress' => round($avg, 1)
                ];
            })->sortByDesc('avg_progress');

        // 6. Colaboradores que NUNCA han ingresado
        $neverLoggedIn = User::whereIn('id', $employeeIds)
            ->whereNull('last_login_at')
            ->get(['names', 'profession', 'email']);

        // 7. Progreso por Puesto (Para el nuevo gráfico)
        $progressByProfession = User::whereIn('id', $employeeIds)
            ->get()
            ->groupBy(fn($u) => $u->profession ?: 'Colaborador')
            ->map(function ($users) {
                $ids = $users->pluck('id');
                $avg = Enrollment::whereIn('user_id', $ids)->avg('progress') ?? 0;
                return [
                    'label' => $users->first()->profession ?: 'Colaborador',
                    'avg' => round($avg, 1),
                    'count' => $users->count()
                ];
            });

        $professionStats = [
            'labels' => $progressByProfession->pluck('label')->toArray(),
            'data' => $progressByProfession->pluck('avg')->toArray(),
            'counts' => $progressByProfession->pluck('count')->toArray(), // <--- Añadido para el gráfico
        ];

        // 8. Cursos más populares (Top 5)
        $popularCourses = Enrollment::whereIn('user_id', $employeeIds)
            ->with('course')
            ->get()
            ->groupBy('course_id')
            ->map(fn($e) => [
                'course' => $e->first()->course->title ?? 'N/A',
                'total' => $e->count()
            ])->sortByDesc('total')->take(5);

        $popularCoursesStats = [
            'labels' => $popularCourses->pluck('course')->toArray(),
            'data' => $popularCourses->pluck('total')->toArray(),
        ];

        // 9. Estado de inscripciones
        $enrollmentStats = [
            'completed'   => Enrollment::whereIn('user_id', $employeeIds)->where('progress', 100)->count(),
            'in_progress' => Enrollment::whereIn('user_id', $employeeIds)->where('progress', '<', 100)->count(),
        ];

        // 10. Exámenes
        $examStats = [
            'passed' => ExamAttempt::whereIn('user_id', $employeeIds)->where('passed', true)->count(),
            'failed' => ExamAttempt::whereIn('user_id', $employeeIds)->where('passed', false)->count(),
        ];

        // 11. Certificados obtenidos en los últimos 6 meses
        $certMonths = [];
        $certCounts = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $certMonths[] = ucfirst($date->translatedFormat('M y'));
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
            'totalTrained',
            'overallProgressAvg',
            'userProgress',
            'topActivities',
            'enrollmentStats',
            'examStats',
            'popularCoursesStats',
            'certificateStats',
            'collaboratorProgress',
            'neverLoggedIn',
            'professionStats'
        ));
    }
}