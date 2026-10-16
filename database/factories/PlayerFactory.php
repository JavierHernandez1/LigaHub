<?php

namespace Database\Factories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Player>
 */
class PlayerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'document_number' => fake()->unique()->bothify('DNI-########?'),
            'birth_date' => fake()->dateTimeBetween('-36 years', '-16 years')->format('Y-m-d'),
            'preferred_foot' => fake()->randomElement(['right', 'left', 'both']),
        ];
    }
}
