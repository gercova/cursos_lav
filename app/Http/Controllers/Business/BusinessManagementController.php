<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Http\Requests\StaffValidate;
use App\Models\CompanyPolicy;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class BusinessManagementController extends Controller {

    public function __construct(){
        $this->middleware(['auth:sanctum', 'business', 'prevent.back']);
    }

    public function index(Request $request): View {
        $codeE = User::where('id', Auth::id())->first();
        $query = User::withCount(['enrollments', 'courses', 'certificates', 'examAttempts'])->where('users.company_code', $codeE->company_code)->orderBy('created_at', 'desc');

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

        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $users = $query->paginate(10);

        $stats = [
            'total'         => User::where('users.company_code', $codeE->company_code)->count(),
            'students'      => User::where('users.company_code', $codeE->company_code)->count(),
            // 'instructors'   => User::where('role', 'instructor')->count(),
            // 'admins'        => User::where('role', 'admin')->count(),
        ];

        return view('business.index', compact('users', 'stats'));
    }

    public function storeStaff(StaffValidate $request): JsonResponse {
        $codeE      = User::where('id', Auth::id())->first();
    
        $countUser  = User::where('company_code', $codeE->company_code)->count();
        $limitUser  = CompanyPolicy::where('user_id', Auth::id())->get();

        if($countUser == (int) $limitUser->quantity + 1){
            return response()->json([
                'status' 	=> false,
                'messages' 	=> 'Ya no puedes registrar más usuarios, solicita cambio de plan al administrador',
            ]);
        } else {
            $validated  = $request->validated();
            $id         = $request->input('user_id');

            $data       = array_merge($validated, [
                'company_code' => $codeE->company_code,
            ]);
            
            DB::beginTransaction();
            try {
                $result = User::updateOrCreate(['id' => $id], $data);

                if ($request->has('role_id')) {
                    // Eliminar todos los roles actuales (asumiendo que un usuario solo tiene un rol)
                    DB::table('model_has_roles')->where('model_id', $result->id)->delete();
                    // Asignar el nuevo rol
                    DB::table('model_has_roles')->insert([
                        'role_id'       => $request->input('role_id'),
                        'model_type'    => 'App\Models\User',
                        'model_id'      => $result->id
                    ]);
                }

                DB::commit();
                return response()->json([
                    'status' 	=> true,
                    'messages' 	=> empty($id) ? 'Datos del usuario actualizado exitosamente' : 'Se ha añadido un nuevo usuario',
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' 	=> false,
                    'messages' 	=> $e->getMessage(),
                ], 500);
            }
        }
    }
}
