<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CartFactory extends Factory
{
    protected $model = Cart::class;
    public function definition() {
        $user   = User::inRandomOrder()->first();
        $course = Course::inRandomOrder()->first();
        return [
            'user_id'   => $user->id,
            'course_id' => $course->id,
        ];
    }

    // public function forUser(User $user) {
    //     return $this->state(fn (array $attributes) => [
    //         'user_id' => $user->id,
    //     ]);
    // }

    // public function forCourse(Course $course) {
    //     return $this->state(fn (array $attributes) => [
    //         'course_id' => $course->id,
    //     ]);
    // }
}
