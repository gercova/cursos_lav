<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()]);
        Role::create(['name' => 'instructor', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()]);
        Role::create(['name' => 'student', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()]);
    }
}
