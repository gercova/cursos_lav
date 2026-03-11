<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CodeValidate;
use App\Http\Requests\PasswordValidate;
use App\Http\Requests\UserValidate;
use App\Models\CompanyPolicy;
use App\Models\Course;
use App\Models\CoursePromotionCode;
use App\Models\User;
use App\Models\UserSignature;
use App\Services\StudentTrackingService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class UserAdminController extends Controller {

    public function __construct() {
        $this->middleware(['auth:sanctum', 'admin', 'prevent.back']);
        $this->middleware('permission:view_users')->only('index');
		$this->middleware('permission:create_users')->only('create');
		$this->middleware('permission:edit_users')->only('edit');
		$this->middleware('permission:delete_users')->only('detroy');
    }

    public function index(Request $request): View {
        $roles = Role::get();
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

        return view('admin.users.index', compact('users', 'stats', 'roles'));
    }

    public function create(): View {
        $roles = Role::get();
        $originalArray = [
            ['code' => '+51', 'country' => '+51 - Perú'],
            ['code' => '+54', 'country' => '+54 - Argentina'],
            ['code' => '+56', 'country' => '+56 - Chile'],
            ['code' => '+591', 'country' => '+591 - Bolivia'],
            ['code' => '+593', 'country' => '+593 - Ecuador'],
            ['code' => '+598', 'country' => '+598 - Uruguay'],
        ];

        $codeCountries = collect($originalArray)->map(fn ($item) => (object) $item);
        return view('admin.users.create', compact('roles', 'codeCountries'));
    }

    public function edit(User $user): View {
        $roles      = Role::get();
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

        // Manejar la subida de la foto de perfil
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $validated['profile_photo'] = $path;
        }
        
        // Determinar si es creación por la presencia de ID en el request
        if (!$request->has('id') || empty($request->id)) {
            $request->role == 'business' ? 
                $proccessData['company_code'] = $this->createNickname($validated['names']) : 
                $proccessData['company_code'] = '';
            $proccessData = [
                'password'          => Hash::make('P4$$w0rd#.'),
                'email_verified_at' => now(),
            ];

            $data = array_merge($validated, $proccessData);
            // Creación - usar email como identificador único
            $user = User::updateOrCreate(['id' => $request->input('id')], $data);

            if ($request->hasFile('signature_photo')) {

                $path_signature = $request->file('signature_photo')->store('signature-photos', 'public');

                UserSignature::updateOrCreate(['id' => $request->input('signature_id')] ,[
                    'user_id'   => $user->id,
                    'signature' => $path_signature,
                ]);
            }
        } else {
            // Actualización - usar ID del request
            $user = User::where('id', $request->id)->first();
            
            // Si hay nueva foto, eliminar la anterior (opcional)
            if ($request->hasFile('profile_photo') && $user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            
            $user->update($validated);
        }
        // Determinar si es creación por la presencia de ID en el request
        // if (!$request->has('id') || empty($request->id)) {
        //     $request->role == 'business' ? 
        //         $proccessData['company_code'] = $this->createNickname($validated['names']) : 
        //         $proccessData['company_code'] = '';
        //     $proccessData = [
        //         'password'          => Hash::make('P4$$w0rd#.'),
        //         'email_verified_at' => now(),
        //     ];

        //     $data = array_merge($validated, $proccessData);
        //     // Creación - usar email como identificador único
        //     $user = User::updateOrCreate(
        //         ['id' => $request->input('id')],
        //         $data
        //     );
        // } else {
        //     // Actualización - usar ID del request
        //     $user = User::where('id', $request->id)->first();
        //     $user->update($validated);
        // }

        if ($request->has('role')) {
            // Eliminar todos los roles actuales (asumiendo que un usuario solo tiene un rol)
            DB::table('model_has_roles')->where('model_id', $user->id)->delete();
            // Asignar el nuevo rol
            $findRoleId = DB::table('roles')->where('name', $request->role)->first();
            DB::table('model_has_roles')->insert([
                'role_id'       => $findRoleId->id,
                'model_type'    => 'App\Models\User',
                'model_id'      => $user->id
            ]);
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

        // Nuevo: Obtener estadísticas de seguimiento para estudiantes
        $trackingData = [];
        if ($user->role === 'student') {
            $trackingService = new StudentTrackingService($user);
            $trackingData = [
                'sessions'          => $trackingService->getSessionsByDay(),
                'course_progress'   => $trackingService->getCourseProgress(),
                'activity_by_type'  => $trackingService->getActivityByType(),
                'active_hours'      => $trackingService->getActiveHours(),
                'devices_used'      => $trackingService->getDevicesUsed(),
                'overall_stats'     => $trackingService->getOverallStats(),
                'avg_session_time'  => $trackingService->getAverageSessionTime()
            ];
        }

        return view('admin.users.show', compact(
            'user', 
            'enrollmentStats', 
            'certificateStats',
            'trackingData'
        ));
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

    public function createCode(User $user, CodeValidate $request) {
        $validated = $request->validated();

        if($user->where('code', null)->where('id', $user->id)->first()){
            $newCode = $this->createNickname($user->names);
            $user->update(['code' => $newCode, 'promotion_price_is_active' => $validated['promotion_price_is_active']]);
        }

        return response()->json([
            'success'   => true,
            'type'      => 'success',
            'message'   => 'Código de promoción creado exitosamente',
            'data'      => [
                'code'  => $user->code,
            ]
        ], 200);
    }

    // public function getLimitUser(User $user): JsonResponse {
    //     return response()->json(CompanyPolicy::where('user_id', $user->id)->first(), 200);
    // }

    public function createLimitUser(Request $request, User $user): JsonResponse {
        $rules = [
            'quantity' => 'required|numeric|min:1',
        ];
        
        $messages = [
            'quantity.required' => 'El campo cantidad es obligatorio.',
            'quantity.numeric'  => 'La cantidad debe ser un número válido.',
            'quantity.min'      => 'La cantidad debe ser al menos :min.',
        ];
        
        $attributes = [
            'quantity' => 'cantidad de usuarios',
        ];
    
        $request->validate($rules, $messages, $attributes);
        
        // Usamos updateOrCreate para manejar crear o actualizar
        // Busca por user_id, y actualiza/crea con los campos especificados
        $policy = CompanyPolicy::updateOrCreate(
            ['user_id'  => $user->id], // Condiciones para encontrar el registro
            ['quantity' => $request->input('quantity')] // Valores a insertar o actualizar
        );

        // Verificamos si la operación fue exitosa
        $wasCreated     = $policy->wasRecentlyCreated; // Es true si se creó, false si se actualizó

        return response()->json([
            'success'   => true, // updateOrCreate debería funcionar si la validación pasa
            'message'   => $wasCreated ? 'Política creada exitosamente.' : 'Política actualizada exitosamente.', // Mensaje distinto según acción
            'data'      => $policy, // Opcional: devolver los datos guardados
        ], 200);
    }

    public function createNickname($name): string {
        $nickname   = '';
        $count      = mb_substr_count($name, ' ');
        $p          = explode(' ', $name);

        if ($count == 1) {
            $w = substr($p[0], 0, -3);
            $nickname = $w . $p[1];
        } elseif ($count == 2) {
            $nickname = $p[0][0] . $p[1] . $p[2][0];
        } elseif ($count == 3) {
            $nickname = $p[0][0] . $p[1] . $p[2][0] . $p[3][0];
        } elseif ($count == 4) {
            $nickname = $p[0][0] . $p[1][0] . $p[2][0] . $p[3] . $p[4][0];
        } else {
            // Caso por defecto: usar el primer nombre completo
            $nickname = strtoupper(str_replace(' ', '', $name));
        }
        // Eliminar caracteres no alfanuméricos
        $nickname = preg_replace('/[^a-zA-Z0-9]/', '', $nickname);
        if(User::where('code', $nickname)->count() > 1) {
            $nickname .= 1;
        }

        return strtoupper($nickname);
    }
}
