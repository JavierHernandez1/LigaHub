<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Team>
 */
class TeamFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->city().' FC';

        return [
            'venue_id' => Venue::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(100, 999),
            'city' => fake()->city(),
            'founded_year' => fake()->numberBetween(1940, 2022),
        ];
    }
}
