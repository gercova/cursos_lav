<?php

namespace Database\Factories;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\ExamAttempt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Certificate>
 */
class CertificateFactory extends Factory {

    protected $model = Certificate::class;
    public function definition() {
        $issueDate = $this->faker->dateTimeBetween('-1 year', 'now');

        return [
            'user_id'               => User::factory(),
            'course_id'             => Course::factory(),
            'exam_attempt_id'       => ExamAttempt::factory(),
            'certificate_code'      => 'CERT-' . strtoupper($this->faker->bothify('??##??')) . '-' . date('Ymd'),
            'certificate_number'    => $this->faker->numerify('####-') . date('Y') . '-IPF-EDUCA',
            'issue_date'            => $issueDate,
            'expiry_date'           => $this->faker->optional(0.7)->dateTimeBetween($issueDate, '+2 years'),
            'total_hours'           => $this->faker->randomNumber(2, 10),
            'download_count'        => $this->faker->numberBetween(0, 10),
            'total_hours'           => $this->faker->randomFloat(1, 10, 100),
        ];
    }

    public function withUser(User $user) {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    public function withCourse(Course $course) {
        return $this->state(fn (array $attributes) => [
            'course_id' => $course->id,
        ]);
    }

    public function withExamAttempt(ExamAttempt $attempt) {
        return $this->state(fn (array $attributes) => [
            'exam_attempt_id' => $attempt->id,
        ]);
    }

    public function expired() {
        return $this->state(fn (array $attributes) => [
            'expiry_date' => $this->faker->dateTimeBetween('-1 year', '-1 day'),
        ]);
    }

    public function active() {
        return $this->state(fn (array $attributes) => [
            'expiry_date' => $this->faker->dateTimeBetween('+1 day', '+2 years'),
        ]);
    }
}
