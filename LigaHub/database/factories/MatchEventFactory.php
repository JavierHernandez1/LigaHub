<?php

namespace Database\Factories;

use App\Models\Fixture;
use App\Models\MatchEvent;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MatchEvent>
 */
class MatchEventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'fixture_id' => Fixture::factory(),
            'team_id' => Team::factory(),
            'player_id' => Player::factory(),
            'type' => fake()->randomElement(['goal', 'assist', 'yellow_card', 'red_card', 'substitution']),
            'minute' => fake()->numberBetween(1, 90),
            'extra_minute' => fake()->optional(0.2)->numberBetween(1, 8),
            'notes' => fake()->optional(0.25)->sentence(6),
        ];
    }
}
