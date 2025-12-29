<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordValidate;
use App\Http\Requests\ProfileValidate;
use App\Http\Requests\UserValidate;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAdminController extends Controller {

    public function __construct() {
        $this->middleware(['auth:sanctum', 'admin', 'prevent.back']);
    }

    public function index(Request $request): View {
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

    // public function profile(): View {
    //     return view('admin.profile.index');
    // }

    // public function updateProfile(ProfileValidate $request) {
    //     $user = Auth::user();

    //     $validated = $request->validated();

    //     if ($request->filled('current_password')) {
    //         $request->validate([
    //             'current_password'  => 'required|current_password',
    //             'new_password'      => 'required|string|min:8|confirmed',
    //         ]);

    //         $validated['password'] = Hash::make($request->new_password);
    //     }

    //     $user->update($validated);

    //     $this->logActivity("Actualizó su perfil de administrador");

    //     return redirect()->back()->with('success', 'Perfil actualizado exitosamente.');
    // }

    public function create(): View {
        $roles = ['student' => 'Estudiante', 'instructor' => 'Instructor', 'admin' => 'Administrador'];
        return view('admin.users.create', compact('roles'));
    }

    public function edit(User $user): View {
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

    public function store(UserValidate $request) {
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

    public function show(User $user): View {
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

    public function updatePassword(PasswordValidate $request, User $user): JsonResponse {
        $validated = $request->validated();
        $user->update(['password' => Hash::make($validated['password'])]);
        return response()->json([
            'success' => true,
            'message' => 'Contraseña actualizada',
        ], 200);
    }

    public function destroy(User $user): JsonResponse {
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

    public function toggleStatus(User $user): JsonResponse {
        $user->update([
            'is_active' => !$user->is_active
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Estado del usuario actualizado.',
            'status'    => $user->is_active
        ]);
    }
}
