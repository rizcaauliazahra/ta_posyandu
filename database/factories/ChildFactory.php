<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChildFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->firstName(),
            'birth_date' => fake()->dateTimeBetween('-5 years', '-1 month')->format('Y-m-d'),
            'gender' => fake()->randomElement(['male', 'female']),
        ];
    }
}
