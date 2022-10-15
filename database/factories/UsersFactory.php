<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\users>
 */
class UsersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'role' => $this->faker->numberBetween(1, 2),
            'status' => $this->faker->numberBetween(1, 0),
            'password' => \Illuminate\Support\Facades\Hash::make('123456789'),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($user) {
            $user->user_details()->create([
                'address' => $this->faker->address,
            ]);
        });
    }
    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
