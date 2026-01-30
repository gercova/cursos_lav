<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamAttempt>
 */
class ExamAttemptFactory extends Factory {

    protected $model = ExamAttempt::class;
    public function definition() {
        $startedAt  = $this->faker->dateTimeBetween('-1 month', 'now');
        $passed     = $this->faker->boolean(70);
        $score      = $passed ? $this->faker->numberBetween(70, 100) : $this->faker->numberBetween(0, 69);

        return [
            'exam_id'           => Exam::factory(),
            'user_id'           => User::factory(),
            'score'             => $score,
            'total_points'      => $this->faker->numberBetween(50, 100),
            'passed'            => $passed,
            'started_at'        => $startedAt,
            'completed_at'      => $this->faker->dateTimeBetween($startedAt, '+2 hours'),
            'answers'           => json_encode($this->generateAnswers()),
            'attempt_number'    => $this->faker->numberBetween(1, 3),
        ];
    }

    private function generateAnswers() {
        $answers = [];
        $questionCount = $this->faker->numberBetween(10, 30);

        for ($i = 1; $i <= $questionCount; $i++) {
            $answers[] = [
                'question_id'       => $i,
                'selected_option'   => $this->faker->randomElement(['A', 'B', 'C', 'D']),
                'is_correct'        => $this->faker->boolean(70),
                'points_earned'     => $this->faker->randomFloat(1, 0, 5),
            ];
        }

        return $answers;
    }

    public function withExam(Exam $exam) {
        return $this->state(fn (array $attributes) => [
            'exam_id' => $exam->id,
        ]);
    }

    public function withUser(User $user) {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    public function passed() {
        return $this->state(fn (array $attributes) => [
            'passed'    => true,
            'score'     => $this->faker->numberBetween(70, 100),
        ]);
    }

    public function failed() {
        return $this->state(fn (array $attributes) => [
            'passed'    => false,
            'score'     => $this->faker->numberBetween(0, 69),
        ]);
    }

    public function inProgress() {
        return $this->state(fn (array $attributes) => [
            'completed_at'  => null,
            'passed'        => null,
            'score'         => null,
        ]);
    }

    public function firstAttempt() {
        return $this->state(fn (array $attributes) => [
            'attempt_number' => 1,
        ]);
    }

    public function retryAttempt() {
        return $this->state(fn (array $attributes) => [
            'attempt_number' => $this->faker->numberBetween(2, 5),
        ]);
    }
}
