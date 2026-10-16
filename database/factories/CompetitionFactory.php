<?php

namespace Database\Factories;

use App\Models\Competition;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Competition>
 */
class CompetitionFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->randomElement(['Liga Municipal', 'Copa Primavera', 'Torneo LigaHub', 'Liga Metropolitana']).' '.fake()->city();

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(100, 999),
            'description' => fake()->sentence(12),
            'type' => fake()->randomElement(['league', 'cup', 'tournament']),
            'organizer' => fake()->company(),
        ];
    }
}
