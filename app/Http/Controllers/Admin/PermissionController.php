<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller {
    
    public function __construct() {
        // $this->middleware('permission:view_permissions')->only('index');
        // $this->middleware('permission:create_permissions')->only('create', 'store');
        // $this->middleware('permission:edit_permissions')->only('edit', 'update');
        // $this->middleware('permission:delete_permissions')->only('destroy');
    }

    public function index(): View {
        $permissions    = Permission::withCount('roles')->orderBy('name')->get();
        $modules        = $this->getModules();
        return view('admin.permissions.index', compact('permissions', 'modules'));
    }

    public function create(): View {
        $modules = $this->getModules();
        return view('admin.permissions.create', compact('modules'));
    }

    public function show(Permission $permission): View {
        // Obtener roles que tienen este permiso
        $rolesWithPermission = $permission->roles()->withCount('users')->get();
        
        // Obtener usuarios que tienen este permiso directamente (no a través de roles)
        $usersWithPermission = $permission->users()->get();
        
        // Obtener estadísticas
        $totalRoles = $rolesWithPermission->count();
        $totalUsers = $rolesWithPermission->sum('users_count') + $usersWithPermission->count();
        
        // Obtener módulo del permiso
        $module = explode('_', $permission->name)[1] ?? 'general';
        $moduleName = $this->getModules()[$module] ?? ucfirst($module);
        
        // Obtener acción del permiso (create, view, edit, delete, etc)
        $action = explode('_', $permission->name)[0] ?? 'unknown';
        
        return view('admin.permissions.show', compact(
            'permission',
            'rolesWithPermission',
            'usersWithPermission',
            'totalRoles',
            'totalUsers',
            'module',
            'moduleName',
            'action'
        ));
    }

    public function store(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|unique:permissions,name',
            'module'        => 'required|string',
            'description'   => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'   => false,
                'errors'    => $validator->errors()
            ], 422);
        }

        $permission = Permission::create([
            'name'          => $request->name,
            'guard_name'    => 'web',
            'description'   => $request->description,
        ]);

        return response()->json([
            'success'       => true,
            'message'       => 'Permiso creado exitosamente.',
            'permission'    => $permission
        ]);
    }

    public function edit(Permission $permission): JsonResponse {
        $modules        = $this->getModules();
        $currentModule  = explode('_', $permission->name)[1] ?? 'general';
        
        return response()->json([
            'success'       => true,
            'permission'    => $permission,
            'modules'       => $modules,
            'currentModule' => $currentModule
        ]);
    }

    public function update(Request $request, Permission $permission): JsonResponse {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|unique:permissions,name,' . $permission->id,
            'module'        => 'required|string',
            'description'   => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'   => false,
                'errors'    => $validator->errors()
            ], 422);
        }

        $permission->update([
            'name'          => $request->name,
            'description'   => $request->description,
        ]);

        return response()->json([
            'success'       => true,
            'message'       => 'Permiso actualizado exitosamente.',
            'permission'    => $permission
        ]);
    }

    public function destroy(Permission $permission): JsonResponse {
        // Verificar si el permiso está siendo usado
        if ($permission->roles()->count() > 0 || $permission->users()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el permiso porque está asignado a roles o usuarios.'
            ], 422);
        }

        $permission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Permiso eliminado exitosamente.'
        ]);
    }

    private function getModules() {
        return [
            'dashboard'     => 'Dashboard',
            'enterprise'    => 'Empresa',
            'categories'    => 'Categorías',
            'courses'       => 'Cursos',
            'documents'     => 'Documentos',
            'exams'         => 'Exámenes',
            'users'         => 'Usuarios',
            'enrollments'   => 'Inscripciones',
            'payments'      => 'Pagos',
            'roles'         => 'Roles',
            'permissions'   => 'Permisos',
            'general'       => 'General',
        ];
    }

    public function getPermissionsByModule($module): JsonResponse {
        $permissions = Permission::where('name', 'like', "%_{$module}")->get();
        
        return response()->json([
            'success' => true,
            'permissions' => $permissions
        ]);
    }
}