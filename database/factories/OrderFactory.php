<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Course;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory {

    protected $model = Order::class;
    public function definition() {
        $subtotal   = $this->faker->randomFloat(2, 50, 500);
        $tax        = $subtotal * 0.18;
        $discount   = $this->faker->optional(0.3)->randomFloat(2, 5, 50) ?? '0.00';
        // $discount   = '0.00';
        $total      = $subtotal + $tax - ($discount ?? 0);

        return [
            'user_id'   => User::factory(),
            'subtotal'  => $subtotal,
            'tax'       => $tax,
            'discount'  => $discount,
            'total'     => $total,
            'currency'  => 'PEN',
            'status'    => $this->faker->randomElement(['pending', 'processing', 'completed', 'cancelled', 'refunded']),
            'billing_info' => [
                'name'      => $this->faker->name(),
                'email'     => $this->faker->email(),
                'phone'     => $this->faker->phoneNumber(),
                'address'   => $this->faker->address(),
                'dni'       => $this->faker->numerify('########'),
            ],
            'payment_method' => $this->faker->randomElement(['credit_card', 'paypal', 'bank_transfer', 'cash']),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }

    public function withUser(User $user) {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
            'billing_info' => [
                'name'      => $user->names,
                'email'     => $user->email,
                'phone'     => $user->phone,
                'address'   => $user->address,
                'dni'       => $user->dni,
            ],
        ]);
    }

    public function pending() {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    public function completed() {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    public function cancelled() {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }

    public function withDiscount(float $amount) {
        return $this->state(function (array $attributes) use ($amount) {
            $newTotal = $attributes['subtotal'] + $attributes['tax'] - $amount;

            return [
                'discount'  => $amount,
                'total'     => max(0, $newTotal),
            ];
        });
    }

    public function withSubtotal(float $amount) {
        return $this->state(function (array $attributes) use ($amount) {
            $tax    = $amount * 0.18;
            $total  = $amount + $tax - ($attributes['discount'] ?? 0);

            return [
                'subtotal'  => $amount,
                'tax'       => $tax,
                'total'     => max(0, $total),
            ];
        });
    }

    public function lowValue() {
        return $this->withSubtotal($this->faker->randomFloat(2, 20, 100));
    }

    public function highValue() {
        return $this->withSubtotal($this->faker->randomFloat(2, 300, 1000));
    }
}
