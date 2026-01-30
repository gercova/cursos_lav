<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseSection;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseSectionFactory extends Factory {

    protected $model = CourseSection::class;
    // public function definition(): array {
    //     $course = Course::InRandomOrder()->first();
    //     return [
    //         'course_id'     => $course->id,
    //         'title'         => $this->faker->text(20),
    //         'description'   => $this->faker->text(50),
    //         'order'         => $this->orderCourse($course->id),
    //         'is_active'     => true,
    //     ];
    // }

    // public function orderCourse($courseId) {
    //     $order  = 0;
    //     $courseSection = CourseSection::where('course_id', $courseId)->first();
    //     if($courseSection == 0) {
    //         $order = 1;
    //     } else {
    //         $order  += (int) $courseSection->order;
    //     }
    //     return $order;
    // }

    public function definition(): array {
        // Usar firstOrCreate para evitar problemas cuando no hay cursos
        $course = Course::inRandomOrder()->first() ?? Course::factory()->create();

        return [
            'course_id'     => $course->id,
            'title'         => $this->faker->unique()->text(20),
            'description'   => $this->faker->text(50),
            'order'         => $this->getNextOrder($course->id),
            'is_active'     => true,
        ];
    }

    /**
     * Obtiene el siguiente número de orden para una sección del curso
     */
    private function getNextOrder(int $courseId): int {
        return CourseSection::where('course_id', $courseId)->max('order') + 1;
    }

    /**
     * Estado para secciones inactivas
     */
    public function inactive(): static {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Estado para secciones con un orden específico
     */
    public function withOrder(int $order): static {
        return $this->state(fn (array $attributes) => [
            'order' => $order,
        ]);
    }

    /**
     * Estado para secciones con un curso específico
     */
    public function forCourse(Course $course): static {
        return $this->state(fn (array $attributes) => [
            'course_id' => $course->id,
            'order' => $this->getNextOrder($course->id),
        ]);
    }
}
