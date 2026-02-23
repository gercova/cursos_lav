<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Exam;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exam>
 */
class ExamFactory extends Factory {

    protected $model = Exam::class;
    public function definition() {
        $course = Course::inRandomOrder()->first();
        return [
            'course_id'     => $course->id,
            'title'         => $this->faker->sentence(4),
            'description'   => $this->faker->paragraph(),
            'duration'      => $this->faker->numberBetween(30, 180),
            'passing_score' => $this->faker->numberBetween(60, 80),
            'max_attempts'  => $this->faker->numberBetween(1, 5),
            'is_active'     => $this->faker->boolean(80),
        ];
    }

    public function withCourse(Course $course) {
        return $this->state(fn (array $attributes) => [
            'course_id' => $course->id,
        ]);
    }

    public function active() {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    public function inactive() {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function shortDuration() {
        return $this->state(fn (array $attributes) => [
            'duration' => $this->faker->numberBetween(15, 45),
        ]);
    }

    public function longDuration() {
        return $this->state(fn (array $attributes) => [
            'duration' => $this->faker->numberBetween(120, 240),
        ]);
    }
}
