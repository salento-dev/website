<?php

namespace Database\Factories;

use App\Models\StoryCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<StoryCategory>
 */
class StoryCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Esperimento', 'Tutorial', 'Riflessione', 'Case Study', 'Open Source', 'DevOps', 'Frontend', 'Backend',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
