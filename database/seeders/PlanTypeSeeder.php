<?php

namespace Database\Seeders;

use App\Models\PlanType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        PlanType::create(['name' => 'Básico']);
        PlanType::create(['name' => 'Básico Pro']);
        PlanType::create(['name' => 'Mediano']);
        PlanType::create(['name' => 'Corporativo']);
        PlanType::create(['name' => 'Empresarial']);
        PlanType::create(['name' => 'Industrial']);
        PlanType::create(['name' => 'Corporativo Plus']);
    }
}
