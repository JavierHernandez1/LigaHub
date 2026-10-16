<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->randomElement(['Senior Masculino', 'Senior Femenino', 'Sub 18', 'Sub 16', 'Veteranos']);

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(100, 999),
            'age_group' => fake()->randomElement(['senior', 'under-18', 'under-16', 'veterans']),
            'gender' => fake()->randomElement(['male', 'female', 'mixed']),
        ];
    }
}
