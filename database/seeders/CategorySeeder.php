<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'SST',
            'Residuos Sólidos',
            'Forestales'
        ];

        foreach ($categories as $category) {
            Category::create([
                'name'          => $category,
                'slug'          => Str::slug($category),
                'description'   => "Cursos de {$category} para todos los niveles.",
                'is_active'     => true,
            ]);
        }
    }
}
