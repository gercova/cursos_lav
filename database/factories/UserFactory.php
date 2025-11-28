<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory {

    public function definition() {
        return [
            'dni'               => $this->faker->unique()->numerify('########'),
            'names'             => $this->faker->name(),
            'email'             => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
            'country_code'      => '+51',
            'phone'             => $this->faker->phoneNumber(),
            'nationality'       => 'Peruana',
            'ubigeo'            => $this->faker->numerify('######'),
            'address'           => $this->faker->address(),
            'profession'        => $this->faker->jobTitle(),
            'role'              => 'student',
            'remember_token'    => Str::random(10),
        ];
    }

    public function unverified() {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
