<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordValidate;
use App\Http\Requests\UserValidate;
use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
        return view('admin.dashboard', compact('stats', 'recentEnrollments', 'popularCourses', 'revenueData'));
    }

    /**
     * Obtener estadísticas para el dashboard
     */
    private function getDashboardStats() {
        $today = Carbon::today();
        $firstDayOfMonth = Carbon::now()->firstOfMonth();

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
            'pending_payments'      => Payment::where('status', 'pending')->count(),
            'active_instructors'    => User::where('role', 'instructor')->count(),
            'total_certificates'    => Certificate::count(),
            'total_exams'           => Exam::count(),
        ];
    }

    /**
     * Obtener inscripciones recientes
     */
    private function getRecentEnrollments() {
        return Enrollment::with(['user', 'course'])->latest()->take(10)->get();
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
            'action' => $action,
            'description' => $action,
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
