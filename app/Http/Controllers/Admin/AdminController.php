<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
    /**
     * Mostrar formulario de login para administradores
     */
    public function showLogin(): View {
        return view('admin.auth.login');
    }

    /**
     * Procesar login de administradores
     */
    public function login(Request $request) {
        $credentials = $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
        ]);

        // Verificar si el usuario es administrador
        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->isAdmin()) {
            return back()->withErrors([
                'email' => 'No tienes permisos para acceder al panel administrativo.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Registrar actividad
            $this->logActivity('Inició sesión en el panel administrativo');

            return redirect()->intended(route('admin.dashboard'))->with('success', '¡Bienvenido al panel administrativo!');
            //return redirect()->route('admin.dashboard')->with('success', '¡Bienvenido al panel administrativo!');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no son correctas.',
        ])->onlyInput('email');
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
     * Gestión de Usuarios
     */
    public function usersIndex(Request $request) {
        $query = User::query();

        // Filtros
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('names', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")->orWhere('dni', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function userShow(User $user): View {
        $enrollments    = Enrollment::with('course')->where('user_id', $user->id)->latest()->get();
        $certificates   = Certificate::with('course')->where('user_id', $user->id)->latest()->get();
        return view('admin.users.show', compact('user', 'enrollments', 'certificates'));
    }

    public function userCreate() {
        return view('admin.users.create');
    }

    public function userStore(Request $request) {
        $validated = $request->validate([
            'dni'           => 'required|string|max:20|unique:users',
            'names'         => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users',
            'password'      => 'required|string|min:8|confirmed',
            'role'          => 'required|in:admin,instructor,student',
            'country_code'  => 'required|string|max:5',
            'phone'         => 'required|string|max:20',
            'nationality'   => 'required|string|max:100',
            'ubigeo'        => 'required|string|max:10',
            'address'       => 'required|string|max:500',
            'profession'    => 'required|string|max:255',
        ]);

        $user = User::create([
            'dni'           => $validated['dni'],
            'names'         => $validated['names'],
            'email'         => $validated['email'],
            'password'      => Hash::make($validated['password']),
            'role'          => $validated['role'],
            'country_code'  => $validated['country_code'],
            'phone'         => $validated['phone'],
            'nationality'   => $validated['nationality'],
            'ubigeo'        => $validated['ubigeo'],
            'address'       => $validated['address'],
            'profession'    => $validated['profession'],
            'email_verified_at' => now(),
        ]);

        $this->logActivity("Creó el usuario: {$user->names} ({$user->role})");
        return redirect()->route('admin.users.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function userEdit(User $user): View {
        return view('admin.users.edit', compact('user'));
    }

    public function userUpdate(Request $request, User $user) {
        $validated = $request->validate([
            'dni'           => 'required|string|max:20|unique:users,dni,' . $user->id,
            'names'         => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role'          => 'required|in:admin,instructor,student',
            'country_code'  => 'required|string|max:5',
            'phone'         => 'required|string|max:20',
            'nationality'   => 'required|string|max:100',
            'ubigeo'        => 'required|string|max:10',
            'address'       => 'required|string|max:500',
            'profession'    => 'required|string|max:255',
        ]);

        // Si se proporciona nueva contraseña
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        $this->logActivity("Actualizó el usuario: {$user->names}");
        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function userDestroy(User $user) {
        // Prevenir eliminación de sí mismo
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $userName = $user->names;
        $user->delete();

        $this->logActivity("Eliminó el usuario: {$userName}");

        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado exitosamente.');
    }

    public function toggleUserStatus(User $user): JsonResponse {
        // Prevenir desactivación de sí mismo
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes desactivar tu propia cuenta.'
            ], 403);
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'activó' : 'desactivó';
        $this->logActivity("{$status} el usuario: {$user->names}");

        return response()->json([
            'success'   => true,
            'is_active' => $user->is_active,
            'message'   => 'Estado del usuario actualizado.'
        ]);
    }

    /**
     * Gestión de Inscripciones
     */
    public function enrollmentsIndex(Request $request): View {
        $query = Enrollment::with(['user', 'course']);

        // Filtros
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('course_id') && $request->course_id) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('names', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $enrollments = $query->latest()->paginate(20);
        $courses = Course::where('is_active', true)->get();

        return view('admin.enrollments.index', compact('enrollments', 'courses'));
    }

    public function enrollmentShow(Enrollment $enrollment): View {
        $enrollment->load(['user', 'course', 'payments']);
        return view('admin.enrollments.show', compact('enrollment'));
    }

    public function updateEnrollmentStatus(Request $request, Enrollment $enrollment) {
        $request->validate([
            'status' => 'required|in:active,completed,cancelled'
        ]);

        $oldStatus = $enrollment->status;
        $enrollment->update(['status' => $request->status]);

        $this->logActivity("Cambió estado de inscripción #{$enrollment->id} de {$oldStatus} a {$request->status}");

        return response()->json([
            'success' => true,
            'message' => 'Estado de la inscripción actualizado.'
        ]);
    }

    /**
     * Gestión de Pagos
     */
    public function paymentsIndex(Request $request): View {
        $query = Payment::with(['enrollment.user', 'enrollment.course']);
        // Filtros
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->latest()->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    public function updatePaymentStatus(Request $request, Payment $payment): JsonResponse {
        $request->validate([
            'status' => 'required|in:pending,completed,failed,refunded'
        ]);

        $oldStatus = $payment->status;
        $payment->update([
            'status'    => $request->status,
            'paid_at'   => $request->status === 'completed' ? now() : null
        ]);

        $this->logActivity("Actualizó pago #{$payment->id} de {$oldStatus} a {$request->status}");

        return response()->json([
            'success' => true,
            'message' => 'Estado del pago actualizado.'
        ]);
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
        $totalEnrollments = Enrollment::count();
        $completedEnrollments = Enrollment::where('status', 'completed')->count();

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
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|email',
            'currency' => 'required|string|size:3',
            'timezone' => 'required|timezone',
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
            'names'     => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'phone'     => 'required|string|max:20',
            'address'   => 'required|string|max:500',
            'profession' => 'required|string|max:255',
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

    /**
     * Cerrar sesión
     */
    public function logout(Request $request) {
        $this->logActivity('Cerró sesión del panel administrativo');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('success', 'Sesión cerrada exitosamente.');
    }
}
