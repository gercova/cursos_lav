<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;
    public function definition(): array {
        $category       = Category::inRandomOrder()->where('id', '<>', 4)->first();
        $user           = User::inRandomOrder()->where('role', 'instructor')->first();
        $price          = $this->faker->randomFloat(2, 20, 200);
        $hasPromotion   = $this->faker->boolean(40);
        $promotionPrice = $hasPromotion ? $this->faker->randomFloat(2, $price * 0.5, $price * 0.9) : null;
        $finalPrice     = $promotionPrice ?? $price;

        return [
            'title'             => $title = $this->faker->text(20),
            'meta_description'  => $this->faker->text(30),
            'meta_keywords'     => $this->faker->text(30),
            'slug'              => Str::slug($title),
            'description'       => $this->faker->text(50),
            'learning_outcomes' => $this->faker->text(50),
            'short_description' => $this->faker->text(20),
            'image_url'         => null,
            'price'             => $price,
            'promotion_price'   => $promotionPrice,
            'category_id'       => $category->id,
            'instructor_id'     => $user->id,
            'duration'          => 5,
            'is_active'         => true,
            'requirements'      => $this->generateRequirementsArray(6),
            'what_you_learn'    => $this->generateRequirementsArray(6),
        ];
    }

    // Función principal para llenar el array
    function generateRequirementsArray($cantidad) {
        $resultArray = [];

        for ($i = 0; $i < $cantidad; $i++) {
            $val = $this->faker->text(10); // Ahora sí recibimos el valor
            $resultArray[] = $val; // Forma moderna y rápida de hacer push
        }

        return $resultArray;
    }

    // Ejecución
}
