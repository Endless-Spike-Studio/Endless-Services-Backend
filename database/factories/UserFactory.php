<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make(123456),
        ];
    }

    public function withPassword(string $password): UserFactory
    {
        return $this->state([
            'password' => Hash::make($password),
        ]);
    }

    public function unverified(): UserFactory
    {
        return $this->state([
            'email_verified_at' => null,
        ]);
    }
}
