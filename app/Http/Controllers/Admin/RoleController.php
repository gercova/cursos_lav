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

    public function index(Request $request): View {
        $roles = Role::with('permissions')->get();

        $query = User::with(['roles', 'permissions'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('names', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $roleFilter = $request->role;
            $query->where(function ($q) use ($roleFilter) {
                $q->where('role', $roleFilter)
                  ->orWhereHas('roles', function ($r) use ($roleFilter) {
                      $r->where('name', $roleFilter);
                  });
            });
        }

        $users = $query->paginate(15)->appends($request->query());

        // Adjuntar permisos efectivos a cada usuario de la página actual
        $users->getCollection()->transform(function ($user) {
            $user->effective_permissions = $user->getAllPermissions()->pluck('name')->toArray();
            return $user;
        });

        $permissions = Permission::all()->groupBy(function ($permission) {
            $parts = explode('_', $permission->name, 2);
            return count($parts) > 1 ? $parts[1] : 'general';
        });

        $stats = [
            'total_users' => User::count(),
            'admins'      => User::where('role', 'admin')->orWhereHas('roles', fn($r) => $r->where('name', 'admin'))->count(),
            'instructors' => User::where('role', 'instructor')->orWhereHas('roles', fn($r) => $r->where('name', 'instructor'))->count(),
            'students'    => User::where('role', 'student')->orWhereHas('roles', fn($r) => $r->where('name', 'student'))->count(),
        ];

        return view('admin.roles.index', compact('roles', 'permissions', 'users', 'stats'));
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
        $validated = $request->validate([
            'role'        => ['nullable', 'string', Rule::in(['admin', 'instructor', 'student'])],
            'permissions' => ['nullable', 'array'],
        ]);

        // Actualizar rol del usuario en BD y Spatie si se especifica
        if (!empty($validated['role'])) {
            $newRole = $validated['role'];
            $user->role = $newRole;
            $user->save();
            $user->syncRoles([$newRole]);
        }

        $currentRole = $user->role;

        if ($currentRole === 'student') {
            // Estudiantes no tienen permisos directos asignados
            $user->syncPermissions([]);
        } else {
            // Sincronizar permisos directos para admin / instructor
            $user->syncPermissions($request->permissions ?? []);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Rol y permisos del usuario actualizados exitosamente.'
            ]);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Rol y permisos actualizados exitosamente.');
    }
}