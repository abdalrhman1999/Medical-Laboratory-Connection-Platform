<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
* @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
*/

class UserFactory extends Factory {
    /**
    * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            // 'email_verified_at' => now(),
            'password' => Hash::make('123123123'), // password
            // 'remember_token' => Str::random(10),
            'phone_number'=>'12345678',
           'role_id'=>5,
           'latitude'=>1,
           'longitude'=>1,
            // 'image'=>,
           'point'=>0,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
    */

    public function unverified(): static {
        return $this->state( fn ( array $attributes ) => [
            'email_verified_at' => null,
        ] );
    }
}
