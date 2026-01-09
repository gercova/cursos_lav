<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory {

    protected $model = OrderItem::class;

    public function definition() {
        $price          = $this->faker->randomFloat(2, 20, 200);
        $hasPromotion   = $this->faker->boolean(40);
        $promotionPrice = $hasPromotion ? $this->faker->randomFloat(2, $price * 0.5, $price * 0.9) : null;
        $finalPrice     = $promotionPrice ?? $price;
        $course         = Course::factory()->create();

        return [
            'order_id'          => Order::factory(),
            'course_id'         => $course->id,
            'course_title'      => $course->title,
            'course_image'      => $course->image,
            'price'             => $price,
            'promotion_price'   => $promotionPrice,
            'final_price'       => $finalPrice,
        ];
    }

    public function withOrder(Order $order) {
        return $this->state(fn (array $attributes) => [
            'order_id' => $order->id,
        ]);
    }

    public function withCourse(Course $course) {
        return $this->state(fn (array $attributes) => [
            'course_id'     => $course->id,
            'course_title'  => $course->title,
            'course_image'  => $course->image,
        ]);
    }

    public function withPromotion() {
        return $this->state(function (array $attributes) {
            $promotionPrice = $attributes['price'] * $this->faker->randomFloat(2, 0.5, 0.9);

            return [
                'promotion_price'   => $promotionPrice,
                'final_price'       => $promotionPrice,
            ];
        });
    }

    public function withoutPromotion() {
        return $this->state(fn (array $attributes) => [
            'promotion_price'   => null,
            'final_price'       => $attributes['price'],
        ]);
    }

    public function cheap() {
        return $this->state(fn (array $attributes) => [
            'price'         => $this->faker->randomFloat(2, 5, 30),
            'final_price'   => $this->faker->randomFloat(2, 5, 30),
        ]);
    }

    public function expensive() {
        return $this->state(fn (array $attributes) => [
            'price'         => $this->faker->randomFloat(2, 150, 500),
            'final_price'   => $this->faker->randomFloat(2, 150, 500),
        ]);
    }
}
