<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Contracts\View\View;

class RoleController extends Controller
{
    public function __construct() {
        $this->middleware('permission:view_roles')->only('index', 'show');
        $this->middleware('permission:create_roles')->only('create', 'store');
        $this->middleware('permission:edit_roles')->only('edit', 'update');
        $this->middleware('permission:delete_roles')->only('destroy');
        $this->middleware('permission:assign_permissions')->only('assignPermissions', 'updatePermissions');
    }

    public function index(): View {
        $roles          = Role::with('permissions')->get();
        $permissions    = Permission::all()->groupBy(function ($permission) {
            $parts      = explode('_', $permission->name);
            return $parts[1] ?? 'other';
        });
        
        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function store(Request $request) {
        $request->validate([
            'name'          => 'required|string|unique:roles,name',
            'permissions'   => 'array',
        ]);

        $role = Role::create(['name' => $request->name]);
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Rol creado exitosamente.');
    }

    public function update(Request $request, Role $role) {
        $request->validate([
            'name'          => 'required|string|unique:roles,name,' . $role->id,
            'permissions'   => 'array',
        ]);

        $role->update(['name' => $request->name]);
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Rol actualizado exitosamente.');
    }

    public function destroy(Role $role) {
        // No permitir eliminar roles del sistema
        if (in_array($role->name, ['admin', 'instructor', 'student'])) {
            return redirect()->route('admin.roles.index')->with('error', 'No se puede eliminar este rol del sistema.');
        }

        $role->delete();
        
        return redirect()->route('admin.roles.index')->with('success', 'Rol eliminado exitosamente.');
    }

    public function assignPermissions(User $user) {
        // Solo administradores e instructores
        if ($user->isStudent()) {
            return redirect()->route('admin.users.index')->with('error', 'No se pueden asignar permisos a estudiantes.');
        }

        $permissions = Permission::all()->groupBy(function ($permission) {
            $parts = explode('_', $permission->name);
            return $parts[1] ?? 'other';
        });

        $userPermissions = $user->getAllPermissions()->pluck('id')->toArray();
        
        return view('admin.users.permissions', compact('user', 'permissions', 'userPermissions'));
    }

    public function updatePermissions(Request $request, User $user) {
        // Solo administradores e instructores
        if ($user->isStudent()) {
            return redirect()->route('admin.users.index')->with('error', 'No se pueden asignar permisos a estudiantes.');
        }

        $request->validate([
            'permissions' => 'array',
        ]);

        // Sincronizar permisos directos (sin pasar por roles)
        $user->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.users.index')->with('success', 'Permisos actualizados exitosamente.');
    }
}