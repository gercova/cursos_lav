<?php

namespace Database\Factories;

use App\Models\CompletedLessons;
use App\Models\Enrollment;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CompletedLessonsFactory extends Factory {

    protected $model = CompletedLessons::class;
    public function definition(): array {
        $enrollement    = Enrollment::inRandomOrder()->first();
        $lesson         = Lesson::inRandomOrder()->first();
        return [
            'enrollment_id'         => $enrollement->id,
            'lesson_id'             => $lesson->id,
            'completed_at'          => $this->faker->dateTimeBetween('2025-01-01', now())->format('Y-m-d H:i:s'),
            'time_spent_minutes'    => $this->faker->randomNumber(2),
        ];
    }
}
