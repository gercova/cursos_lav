<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{

    protected $model = Payment::class;

    public function definition()
    {
        $status = $this->faker->randomElement(['pending', 'completed', 'failed', 'refunded']);

        return [
            'order_id'          => Order::factory(),
            'user_id'           => User::factory(),
            'payment_id'        => 'PAY-' . strtoupper($this->faker->bothify('??##??##??')),
            'payment_method'    => $this->faker->randomElement(['credit_card', 'paypal', 'bank_transfer']),
            'amount'            => $this->faker->randomFloat(2, 20, 500),
            'currency'          => 'PEN',
            'status'            => $status,
            'payment_details'   => $this->generatePaymentDetails(),
            'error_message'     => $status === 'failed' ? $this->faker->sentence() : null,
            'paid_at'           => $status === 'completed' ? $this->faker->dateTimeBetween('-1 month', 'now') : null,
        ];
    }

    private function generatePaymentDetails() {
        $method = $this->faker->randomElement(['credit_card', 'paypal', 'bank_transfer']);

        if ($method === 'credit_card') {
            return [
                'method'            => 'credit_card',
                'card_last_four'    => $this->faker->numerify('####'),
                'card_brand'        => $this->faker->creditCardType(),
                'transaction_id'    => 'TXN-' . strtoupper($this->faker->bothify('??##??##??')),
            ];
        } elseif ($method === 'paypal') {
            return [
                'method'            => 'paypal',
                'paypal_email'      => $this->faker->email(),
                'transaction_id'    => 'PAYPAL-' . strtoupper($this->faker->bothify('??##??##??')),
            ];
        } else {
            return [
                'method'            => 'bank_transfer',
                'bank_name'         => $this->faker->company(),
                'account_number'    => $this->faker->bankAccountNumber(),
                'transaction_id'    => 'BANK-' . strtoupper($this->faker->bothify('??##??##??')),
            ];
        }
    }

    public function withOrder(Order $order) {
        return $this->state(fn (array $attributes) => [
            'order_id'  => $order->id,
            'user_id'   => $order->user_id,
            'amount'    => $order->total,
            'currency'  => $order->currency,
        ]);
    }

    public function withUser(User $user) {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    public function completed() {
        return $this->state(fn (array $attributes) => [
            'status'        => 'completed',
            'paid_at'       => $this->faker->dateTimeBetween('-1 month', 'now'),
            'error_message' => null,
        ]);
    }

    public function pending() {
        return $this->state(fn (array $attributes) => [
            'status'        => 'pending',
            'paid_at'       => null,
            'error_message' => null,
        ]);
    }

    public function failed() {
        return $this->state(fn (array $attributes) => [
            'status'        => 'failed',
            'paid_at'       => null,
            'error_message' => $this->faker->sentence(),
        ]);
    }

    public function refunded() {
        return $this->state(fn (array $attributes) => [
            'status'    => 'refunded',
            'paid_at'   => $this->faker->dateTimeBetween('-2 months', '-1 month'),
        ]);
    }

    public function creditCard() {
        return $this->state(function (array $attributes) {
            $details = $this->generatePaymentDetails();
            $details['method'] = 'credit_card';
            return [
                'payment_method'    => 'credit_card',
                'payment_details'   => $details,
            ];
        });
    }

    public function paypal() {
        return $this->state(function (array $attributes) {
            $details = $this->generatePaymentDetails();
            $details['method'] = 'paypal';

            return [
                'payment_method'    => 'paypal',
                'payment_details'   => $details,
            ];
        });
    }
}
