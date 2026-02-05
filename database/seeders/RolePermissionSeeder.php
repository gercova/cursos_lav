<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder {
    
    public function run() {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        $permissions = [
            // Dashboard
            'view_dashboard',
            
            // Empresa
            'view_enterprise',
            'edit_enterprise',
            
            // Categorías
            'view_categories',
            'create_categories',
            'edit_categories',
            'delete_categories',
            
            // Cursos
            'view_courses',
            'create_courses',
            'edit_courses',
            'delete_courses',
            
            // Documentos
            'view_documents',
            'create_documents',
            'edit_documents',
            'delete_documents',
            
            // Exámenes
            'view_exams',
            'create_exams',
            'edit_exams',
            'delete_exams',
            
            // Usuarios
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            
            // Inscripciones
            'view_enrollments',
            'create_enrollments',
            'edit_enrollments',
            'delete_enrollments',
            
            // Pagos
            'view_payments',
            'create_payments',
            'edit_payments',
            'delete_payments',
            
            // Roles y Permisos
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
            'assign_permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crear roles
        $adminRole      = Role::create(['name' => 'admin']);
        $instructorRole = Role::create(['name' => 'instructor']);
        $studentRole    = Role::create(['name' => 'student']);

        // Asignar todos los permisos al admin
        $adminRole->givePermissionTo(Permission::all());

        // Asignar permisos al instructor
        $instructorPermissions = [
            'view_dashboard',
            'view_courses', 'create_courses', 'edit_courses', 'delete_courses',
            'view_documents', 'create_documents', 'edit_documents', 'delete_documents',
            'view_exams', 'create_exams', 'edit_exams', 'delete_exams',
            'view_users',
        ];
        
        $instructorRole->givePermissionTo($instructorPermissions);

        // Asignar permisos básicos al estudiante
        $studentRole->givePermissionTo([
            'view_dashboard',
            'view_courses',
            'view_documents',
            'view_exams',
        ]);

        // Asignar roles a usuarios existentes
        $users = User::all();
        foreach ($users as $user) {
            if ($user->role === 'admin') {
                $user->assignRole('admin');
            } elseif ($user->role === 'instructor') {
                $user->assignRole('instructor');
            } elseif ($user->role === 'student') {
                $user->assignRole('student');
            }
        }
    }
}