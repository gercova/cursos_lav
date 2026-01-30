<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lesson>
 */
class LessonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Lesson::class;
    public function definition(): array {

        $course         = Course::inRandomOrder()->first() ?? Course::factory()->create();
        $courseSection  = CourseSection::inRandomOrder()->first() ?? CourseSection::factory()->create();

        return [
            'course_id'         => $course->id,
            'course_section_id' => $courseSection->id,
            'title'             => $this->faker->text(30),
            'description'       => $this->faker->text(30),
            'video_url'         => $this->faker->url(),
            'duration'          => $this->faker->numberBetween(5, 20),
            'order'             => $this->getNextOrder($courseSection->id),
            'is_free'           => false,
            'is_active'         => true,
        ];
    }

    private function getNextOrder(int $courseSectionId): int {
        return Lesson::where('course_section_id', $courseSectionId)->max('order') + 1;
    }
}
