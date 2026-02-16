<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Payment;
use App\Models\User;
use App\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller {

    public function __construct() {
        $this->middleware(['auth:sanctum', 'admin', 'prevent.back']);
    }

    /**
     * Dashboard principal del administrador
     */
    public function dashboard(): View {
        $stats              = $this->getDashboardStats();
        $recentEnrollments  = $this->getRecentEnrollments();
        $popularCourses     = $this->getPopularCourses();
        $revenueData        = $this->getRevenueData();
        $enrollmentChart    = $this->getEnrollmentChartData();
        $topCoursesChart    = $this->getTopCoursesChartData();
        
        return view('admin.dashboard', compact(
            'stats', 
            'recentEnrollments', 
            'popularCourses', 
            'revenueData',
            'enrollmentChart',
            'topCoursesChart'
        ));
    }

    /**
     * Obtener estadísticas para el dashboard
     */
    private function getDashboardStats() {
        $today = Carbon::today();
        $firstDayOfMonth = Carbon::now()->firstOfMonth();
        $firstDayOfWeek = Carbon::now()->startOfWeek();
        $oneWeekAgo = Carbon::now()->subWeek();

        // Estudiantes activos en la última semana (basado en user_activities)
        $activeStudentsWeek = UserActivity::where('type', UserActivity::TYPE_LOGIN)
            ->where('created_at', '>=', $oneWeekAgo)
            ->distinct('user_id')
            ->count('user_id');

        // Promedio de calificación de cursos (si existe tabla course_reviews)
        try {
            $avgCourseRating = DB::table('course_reviews')->avg('rating') ?? 0;
        } catch (\Exception $e) {
            $avgCourseRating = 0; // Si la tabla no existe
        }

        return [
            'total_students'        => User::where('role', 'student')->count(),
            'total_courses'         => Course::count(),
            'total_categories'      => Category::count(),
            'total_enrollments'     => Enrollment::count(),
            'total_revenue'         => Payment::where('status', 'completed')->sum('amount'),
            'today_enrollments'     => Enrollment::whereDate('enrolled_at', $today)->count(),
            'monthly_revenue'       => Payment::where('status', 'completed')
                ->where('created_at', '>=', $firstDayOfMonth)
                ->sum('amount'),
            'weekly_revenue'        => Payment::where('status', 'completed')
                ->where('created_at', '>=', $firstDayOfWeek)
                ->sum('amount'),
            'pending_payments'      => Payment::where('status', 'pending')->count(),
            'active_instructors'    => User::where('role', 'instructor')->count(),
            'total_certificates'    => Certificate::count(),
            'total_exams'           => Exam::count(),
            'completed_exams'       => ExamAttempt::where('passed', true)->count(),
            'failed_exams'          => ExamAttempt::where('passed', false)->count(),
            'avg_exam_score'        => ExamAttempt::whereNotNull('score')->avg('score') ?? 0,
            'avg_course_rating'     => $avgCourseRating,
            'active_students_week'  => $activeStudentsWeek,
            'instructors_with_courses' => User::where('role', 'instructor')
                ->whereHas('courses')
                ->count(),
            'enrollments_today'     => Enrollment::whereDate('created_at', $today)->count(),
        ];
    }

    /**
     * Obtener inscripciones recientes
     */
    // private function getRecentEnrollments() {
    //     return Enrollment::with(['user', 'course'])->latest()->take(10)->get();
    // }
    private function getRecentEnrollments() {
        return Enrollment::with(['user', 'course'])
            ->latest()
            ->take(10)
            ->get()
            ->filter(function($enrollment) {
                // Filtramos después de la consulta para evitar errores en la vista
                return !is_null($enrollment->user) && !is_null($enrollment->course);
            })
            ->values(); // Reindexa la colección
    }

    /**
     * Obtener cursos más populares
     */
    private function getPopularCourses() {
        return Course::withCount('enrollments')->with('category')->orderBy('enrollments_count', 'desc')->take(8)->get();
    }

    /**
     * Obtener datos de ingresos para gráficos
     */
    private function getRevenueData() {
        $revenue = Payment::where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->select(
                DB::raw('SUM(amount) as revenue'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $enrollments = Enrollment::where('enrolled_at', '>=', Carbon::now()->subMonths(6))
            ->select(
                DB::raw('COUNT(*) as count'),
                DB::raw('MONTH(enrolled_at) as month'),
                DB::raw('YEAR(enrolled_at) as year')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        return [
            'revenue'       => $revenue,
            'enrollments'   => $enrollments
        ];
    }

    /**
     * Obtener datos para gráfico de inscripciones por mes
     */
    private function getEnrollmentChartData() {
        $enrollments = Enrollment::select(
                DB::raw('COUNT(*) as count'),
                DB::raw('MONTH(enrolled_at) as month'),
                DB::raw('YEAR(enrolled_at) as year'),
                DB::raw('DATE_FORMAT(enrolled_at, "%b") as month_name')
            )
            ->where('enrolled_at', '>=', Carbon::now()->subMonths(11))
            ->groupBy('year', 'month', 'month_name')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $labels = [];
        $data = [];
        
        foreach ($enrollments as $enrollment) {
            $labels[]   = $enrollment->month_name . ' ' . $enrollment->year;
            $data[]     = $enrollment->count;
        }

        return [
            'labels'    => $labels,
            'data'      => $data
        ];
    }

    /**
     * Obtener datos para gráfico de cursos con mayor demanda
     */
    private function getTopCoursesChartData() {
        $courses = Course::select('title', 'id')
            ->withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->take(10)
            ->get();

        $labels = [];
        $data = [];
        $colors = [];
        
        // Generar colores pastel para el gráfico
        $pastelColors = [
            'rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)', 'rgba(153, 102, 255, 0.7)', 'rgba(255, 159, 64, 0.7)',
            'rgba(199, 199, 199, 0.7)', 'rgba(83, 102, 255, 0.7)', 'rgba(40, 159, 64, 0.7)',
            'rgba(210, 199, 199, 0.7)'
        ];

        foreach ($courses as $index => $course) {
            // Acortar títulos largos para el gráfico
            $shortTitle = strlen($course->title) > 20 
                ? substr($course->title, 0, 20) . '...' 
                : $course->title;
            
            $labels[] = $shortTitle;
            $data[] = $course->enrollments_count;
            $colors[] = $pastelColors[$index % count($pastelColors)];
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => $colors
        ];
    }

    /**
     * Reportes y Analytics
     */
    public function reports(): View {
        $reports = $this->generateReports();
        return view('admin.reports.index', compact('reports'));
    }

    private function generateReports(): array {
        $startOfMonth   = Carbon::now()->firstOfMonth();
        $startOfYear    = Carbon::now()->firstOfYear();

        return [
            'monthly_revenue' => Payment::where('status', 'completed')
                ->where('created_at', '>=', $startOfMonth)
                ->sum('amount'),

            'yearly_revenue' => Payment::where('status', 'completed')
                ->where('created_at', '>=', $startOfYear)
                ->sum('amount'),

            'total_students' => User::where('role', 'student')->count(),

            'monthly_enrollments' => Enrollment::where('enrolled_at', '>=', $startOfMonth)
                ->count(),

            'completion_rate' => $this->calculateCompletionRate(),

            'top_courses' => Course::withCount('enrollments')
                ->orderBy('enrollments_count', 'desc')
                ->take(5)
                ->get(),

            'revenue_by_month' => $this->getRevenueByMonth(),
            'student_activity' => $this->getStudentActivity(),
        ];
    }

    private function calculateCompletionRate() {
        $totalEnrollments       = Enrollment::count();
        $completedEnrollments   = Enrollment::where('status', 'completed')->count();

        return $totalEnrollments > 0 ? ($completedEnrollments / $totalEnrollments) * 100 : 0;
    }

    private function getRevenueByMonth() {
        return Payment::where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subYear())
            ->select(
                DB::raw('SUM(amount) as revenue'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
    }

    private function getStudentActivity() {
        return User::where('role', 'student')
            ->withCount(['enrollments', 'examAttempts'])
            ->orderBy('enrollments_count', 'desc')
            ->take(10)
            ->get();
    }

    /**
     * Configuración del Sistema
     */
    public function settings(): View {
        $settings = [
            'site_name'     => config('app.name'),
            'site_email'    => config('mail.from.address'),
            'currency'      => 'PEN',
            'timezone'      => config('app.timezone'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function updateSettings(Request $request) {
        $validated = $request->validate([
            'site_name'     => 'required|string|max:255',
            'site_email'    => 'required|email',
            'currency'      => 'required|string|size:3',
            'timezone'      => 'required|timezone',
        ]);

        // Aquí deberías guardar estas configuraciones en la base de datos
        // o en el archivo de configuración según tu implementación

        $this->logActivity("Actualizó la configuración del sistema");

        return redirect()->back()->with('success', 'Configuraciones actualizadas exitosamente.');
    }

    /**
     * Backup y Mantenimiento
     */
    public function maintenance(): View {
        return view('admin.maintenance.index');
    }

    public function runBackup(): JsonResponse {
        // Ejecutar comando de backup
        Artisan::call('backup:run');

        $this->logActivity("Ejecutó backup del sistema");

        return response()->json([
            'success' => true,
            'message' => 'Backup ejecutado exitosamente.'
        ]);
    }

    public function clearCache(): JsonResponse {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');

        $this->logActivity("Limpió cache del sistema");

        return response()->json([
            'success' => true,
            'message' => 'Cache limpiado exitosamente.'
        ]);
    }

    /**
     * Log de Actividades
     */
    public function activityLog(Request $request): View {
        $query = DB::table('activity_logs')
            ->leftJoin('users', 'activity_logs.user_id', '=', 'users.id')
            ->select('activity_logs.*', 'users.names as user_name');

        if ($request->has('action') && $request->action) {
            $query->where('action', 'like', "%{$request->action}%");
        }

        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        $activities = $query->orderBy('created_at', 'desc')
            ->paginate(50);

        $users = User::whereIn('id',
            DB::table('activity_logs')->select('user_id')->distinct()
        )->get();

        return view('admin.activity-log.index', compact('activities', 'users'));
    }

    /**
     * Utilidades
     */
    private function logActivity($action) {
        DB::table('activity_logs')->insert([
            'action'        => $action,
            'description'   => $action,
            'user_id'       => Auth::id(),
            'ip_address'    => request()->ip(),
            'user_agent'    => request()->userAgent(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}
