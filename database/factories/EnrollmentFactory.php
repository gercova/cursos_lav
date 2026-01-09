<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EnrollmentFactory extends Factory {

    protected $model = Enrollment::class;
    public function definition(): array {

        $user   = User::inRandomOrder()->first();
        $course = Course::inRandomOrder()->first();
        return [
            'user_id'       => $user->id,
            'course_id'     => $course->id,
            'enrolled_at'   => $this->faker->dateTimeBetween('2024-01-01', '2025-12-31')->format('Y-m-d H:i:s'),
            'completed_at'  => now(),
            'progress'      => '100',
            // 'status'        => $this->randomElement(['active', 'completed', 'cancelled']),
            'status'        => 'completed',
            'created_at'    => $this->faker->dateTimeBetween('2024-01-01', '2025-12-31')->format('Y-m-d H:i:s'),
            'updated_at'    => now(),
        ];
    }
}
