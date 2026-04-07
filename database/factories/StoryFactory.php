<?php

namespace Database\Factories;

use App\Models\StoryCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Story>
 */
class StoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(fake()->numberBetween(4, 8));
        $publishedAt = fake()->dateTimeBetween('-2 years', 'now');

        return [
            'story_category_id' => StoryCategory::factory(),
            'title' => rtrim($title, '.'),
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100, 999),
            'excerpt' => fake()->paragraph(2),
            'body' => implode("\n\n", fake()->paragraphs(fake()->numberBetween(4, 8))),
            'image_path' => null,
            'read_time_minutes' => fake()->numberBetween(3, 20),
            'is_published' => fake()->boolean(80),
            'published_at' => $publishedAt,
        ];
    }
}
