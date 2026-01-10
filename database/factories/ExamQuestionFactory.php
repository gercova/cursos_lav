<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\ExamQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamQuestion>
 */
class ExamQuestionFactory extends Factory {

    protected $model = ExamQuestion::class;
    public function definition() {
        // $type = $this->faker->randomElement(['multiple_choice', 'true_false', 'short_answer']);
        $type = $this->faker->randomElement(['multiple_choice', 'true_false']);
        $options = $this->generateOptions($type);

        return [
            'exam_id'           => Exam::factory(),
            'question'          => $this->faker->sentence(10) . '?',
            'type'              => $type,
            'options'           => $options,
            'correct_answer'    => $this->generateCorrectAnswer($type, $options),
            'points'            => $this->faker->randomFloat(1, 1, 10),
            'order'             => $this->faker->numberBetween(1, 50),
        ];
    }

    private function generateOptions(string $type) {
        if ($type === 'multiple_choice') {
            return [
                'A' => $this->faker->sentence(),
                'B' => $this->faker->sentence(),
                'C' => $this->faker->sentence(),
                'D' => $this->faker->sentence(),
            ];
        } elseif ($type === 'true_false') {
            return [
                'A' => 'Verdadero',
                'B' => 'Falso',
            ];
        }

        return null;
    }

    private function generateCorrectAnswer(string $type, ?array $options) {
        if ($type === 'multiple_choice') {
            return $this->faker->randomElement(['A', 'B', 'C', 'D']);
        } elseif ($type === 'true_false') {
            return $this->faker->randomElement(['A', 'B']);
        }

        return $this->faker->sentence();
    }

    public function withExam(Exam $exam) {
        return $this->state(fn (array $attributes) => [
            'exam_id' => $exam->id,
        ]);
    }

    public function multipleChoice() {
        return $this->state(fn (array $attributes) => [
            'type' => 'multiple_choice',
            'options' => [
                'A' => $this->faker->sentence(),
                'B' => $this->faker->sentence(),
                'C' => $this->faker->sentence(),
                'D' => $this->faker->sentence(),
            ],
            'correct_answer' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
        ]);
    }

    public function trueFalse() {
        return $this->state(fn (array $attributes) => [
            'type' => 'true_false',
            'options'   => [
                'A'     => 'Verdadero',
                'B'     => 'Falso',
            ],
            'correct_answer' => $this->faker->randomElement(['A', 'B']),
        ]);
    }

    public function shortAnswer() {
        return $this->state(fn (array $attributes) => [
            'type'              => 'short_answer',
            'options'           => null,
            'correct_answer'    => $this->faker->sentence(),
        ]);
    }

    public function highPoints() {
        return $this->state(fn (array $attributes) => [
            'points' => $this->faker->randomFloat(1, 8, 15),
        ]);
    }

    public function lowPoints() {
        return $this->state(fn (array $attributes) => [
            'points' => $this->faker->randomFloat(1, 1, 3),
        ]);
    }
}
