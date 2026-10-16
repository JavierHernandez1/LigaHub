<?php

namespace Database\Factories;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Venue>
 */
class VenueFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Campo Municipal', 'Estadio Norte', 'Polideportivo Central', 'Ciudad Deportiva']).' '.fake()->city(),
            'city' => fake()->city(),
            'address' => fake()->streetAddress(),
            'capacity' => fake()->numberBetween(500, 25000),
        ];
    }
}
