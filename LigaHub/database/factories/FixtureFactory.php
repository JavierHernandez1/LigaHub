<?php

namespace Database\Factories;

use App\Models\Fixture;
use App\Models\Season;
use App\Models\Team;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Fixture>
 */
class FixtureFactory extends Factory
{
    public function definition(): array
    {
        return [
            'season_id' => Season::factory(),
            'home_team_id' => Team::factory(),
            'away_team_id' => Team::factory(),
            'venue_id' => Venue::factory(),
            'matchday' => fake()->numberBetween(1, 38),
            'scheduled_at' => fake()->dateTimeBetween('+1 week', '+6 months'),
            'status' => 'scheduled',
            'home_score' => null,
            'away_score' => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'home_score' => fake()->numberBetween(0, 5),
            'away_score' => fake()->numberBetween(0, 5),
        ]);
    }
}
