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

class AdminController extends Controller {

    public function __construct() {
        $this->middleware(['auth:sanctum', 'admin', 'prevent.back']);
    }

    /**
     * Dashboard principal del administrador
     */
    public function dashboard(): View {
        $this->middleware(['auth:sanctum', 'admin']);
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
        $startOfMonth = Carbon::now()->firstOfMonth();
        $startOfYear = Carbon::now()->firstOfYear();

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

    /**
     * Perfil del Administrador
     */
    public function profile(): View {
        return view('admin.profile.index');
    }

    public function updateProfile(Request $request) {
        $user = Auth::user();

        $validated = $request->validate([
            'names'         => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'phone'         => 'required|string|max:20',
            'address'       => 'required|string|max:500',
            'profession'    => 'required|string|max:255',
        ]);

        if ($request->filled('current_password')) {
            $request->validate([
                'current_password' => 'required|current_password',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            $validated['password'] = Hash::make($request->new_password);
        }

        $user->update($validated);

        $this->logActivity("Actualizó su perfil de administrador");

        return redirect()->back()->with('success', 'Perfil actualizado exitosamente.');
    }


    public function usersIndex(Request $request): View {
        $query = User::withCount(['enrollments', 'courses', 'certificates', 'examAttempts'])->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('names', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $users = $query->paginate(10);

        $stats = [
            'total'         => User::count(),
            'students'      => User::where('role', 'student')->count(),
            'instructors'   => User::where('role', 'instructor')->count(),
            'admins'        => User::where('role', 'admin')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function userCreate(): View {
        $roles = ['student' => 'Estudiante', 'instructor' => 'Instructor', 'admin' => 'Administrador'];
        return view('admin.users.create', compact('roles'));
    }

    public function userStore(UserValidate $request) {
        $validated = $request->validated();

        // Determinar si es creación por la presencia de ID en el request
        if (!$request->has('id') || empty($request->id)) {
            $proccessData = [
                'password'          => Hash::make('P4$$w0rd#.'),
                'email_verified_at' => now(),
            ];

            $data = array_merge($validated, $proccessData);
            // Creación - usar email como identificador único
            $user = User::updateOrCreate(
                ['id' => $request->input('id')],
                $data
            );
        } else {
            // Actualización - usar ID del request
            $user = User::where('id', $request->id)->first();
            $user->update($validated);
        }

        $message = $request->has('id') ? 'actualizado' : 'creado';
        return redirect()->route('admin.users.index')->with('success', "Usuario {$message} exitosamente.");
    }

    public function userShow(User $user): View {
        $user->load([
            'enrollments.course.category',
            'courses.category',
            'certificates.course',
            'examAttempts.exam.course',
            'cartItems.course'
        ]);

        $enrollmentStats = $user->enrollments()
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed,
                AVG(progress) as avg_progress
            ')
            ->first();

        $certificateStats = $user->certificates()
            ->selectRaw('COUNT(*) as total')
            ->first();

        return view('admin.users.show', compact('user', 'enrollmentStats', 'certificateStats'));
    }

    public function userEdit(User $user): View {
        $roles = ['student' => 'Estudiante', 'instructor' => 'Instructor', 'admin' => 'Administrador'];
        $originalArray = [
            ['code' => '+51', 'country' => '+51 - Perú'],
            ['code' => '+54', 'country' => '+54 - Argentina'],
            ['code' => '+56', 'country' => '+56 - Chile'],
            ['code' => '+591', 'country' => '+591 - Bolivia'],
            ['code' => '+593', 'country' => '+593 - Ecuador'],
            ['code' => '+598', 'country' => '+598 - Uruguay'],
        ];

        $codeCountries = collect($originalArray)->map(fn ($item) => (object) $item);
        return view('admin.users.edit', compact('user', 'roles', 'codeCountries'));
    }

    public function updatePassword(PasswordValidate $request, User $user): JsonResponse {
        $validated = $request->validated();
        $user->update(['password' => Hash::make($validated['password'])]);
        return response()->json([
            'success' => true,
            'message' => 'Contraseña actualizada',
        ], 200);
    }

    public function userDestroy(User $user): JsonResponse {
        // Verificar que no sea el último admin
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el único administrador.'
            ], 400);
        }

        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado exitosamente.'
        ]);
    }

    public function toggleUserStatus(User $user): JsonResponse {
        $user->update([
            'is_active' => !$user->is_active
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Estado del usuario actualizado.',
            'status'    => $user->is_active
        ]);
    }

    // ==================== GESTIÓN DE INSCRIPCIONES ====================

    public function enrollmentsIndex(Request $request): View {
        $query = Enrollment::with(['user', 'course.category'])->latest();
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

        $enrollments = $query->paginate(20);
        $courses = Course::where('is_active', true)->get();

        $stats = [
            'total'     => Enrollment::count(),
            'active'    => Enrollment::where('status', 'active')->count(),
            'completed' => Enrollment::where('status', 'completed')->count(),
            'cancelled' => Enrollment::where('status', 'cancelled')->count(),
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

    // ==================== GESTIÓN DE PAGOS ====================

    public function paymentsIndex(Request $request): View {
        $query = Payment::with(['user', 'enrollment.course'])
            ->latest();

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('names', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('enrollment.course', function ($q) use ($search) {
                      $q->where('title', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }

        $payments = $query->paginate(20);

        $stats = [
            'total' => Payment::sum('amount'),
            'pending' => Payment::where('status', 'pending')->sum('amount'),
            'completed' => Payment::where('status', 'completed')->sum('amount'),
            'failed' => Payment::where('status', 'failed')->sum('amount'),
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    public function updatePaymentStatus(Request $request, Payment $payment): JsonResponse {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'completed', 'failed', 'refunded'])]
        ]);

        $payment->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Estado del pago actualizado.'
        ]);
    }

}
