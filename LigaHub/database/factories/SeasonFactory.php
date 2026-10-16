<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Competition;
use App\Models\Season;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Season>
 */
class SeasonFactory extends Factory
{
    public function definition(): array
    {
        $startYear = fake()->numberBetween(2024, 2028);

        return [
            'competition_id' => Competition::factory(),
            'category_id' => Category::factory(),
            'name' => $startYear.'/'.($startYear + 1),
            'starts_on' => "{$startYear}-09-01",
            'ends_on' => ($startYear + 1).'-06-30',
            'status' => fake()->randomElement(['draft', 'active', 'completed']),
        ];
    }
}
