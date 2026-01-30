<?php

namespace Database\Factories;

use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LessonProgress>
 */
class LessonProgressFactory extends Factory {

    protected $model = LessonProgress::class;
    public function definition() {
        $completed = $this->faker->boolean(60);

        return [
            'user_id'       => User::factory(),
            'lesson_id'     => Lesson::factory(),
            'enrollment_id' => Enrollment::factory(),
            'completed'     => $completed,
            'progress'      => $completed ? 100 : $this->faker->numberBetween(0, 99),
            'time_watched'  => $this->faker->numberBetween(0, 3600),
            'completed_at'  => $completed ? $this->faker->dateTimeBetween('-1 month', 'now') : null,
        ];
    }

    public function withUser(User $user) {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    public function withLesson(Lesson $lesson)
    {
        return $this->state(fn (array $attributes) => [
            'lesson_id' => $lesson->id,
        ]);
    }

    public function withEnrollment(Enrollment $enrollment)
    {
        return $this->state(fn (array $attributes) => [
            'enrollment_id' => $enrollment->id,
            'user_id' => $enrollment->user_id,
        ]);
    }

    public function completed() {
        return $this->state(fn (array $attributes) => [
            'completed'     => true,
            'progress'      => 100,
            'completed_at'  => $this->faker->dateTimeBetween('-1 month', 'now'),
            'time_watched'  => $this->faker->numberBetween(300, 3600),
        ]);
    }

    public function inProgress() {
        return $this->state(fn (array $attributes) => [
            'completed'     => false,
            'progress'      => $this->faker->numberBetween(1, 99),
            'completed_at'  => null,
            'time_watched'  => $this->faker->numberBetween(0, 1800),
        ]);
    }

    public function notStarted() {
        return $this->state(fn (array $attributes) => [
            'completed'     => false,
            'progress'      => 0,
            'completed_at'  => null,
            'time_watched'  => 0,
        ]);
    }

    public function withProgress(int $progress) {
        return $this->state(fn (array $attributes) => [
            'progress'      => $progress,
            'completed'     => $progress >= 100,
            'completed_at'  => $progress >= 100 ? $this->faker->dateTimeBetween('-1 month', 'now') : null,
        ]);
    }
}
