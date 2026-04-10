<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);
        $startsAt = fake()->dateTimeBetween('-6 months', '+2 months');
        $work_in_progress = ($startsAt > now()) ? fake()->boolean(20) : false;

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100, 999),
            'location' => fake()->city(),
            'address' => fake()->address(),
            'starts_at' => $startsAt,
            'ends_at' => (clone $startsAt)->modify('+2 hours'),
            'excerpt' => fake()->paragraph(),
            'body' => fake()->paragraphs(3, true),
            'image_path' => null,
            'is_published' => fake()->boolean(85),
            'work_in_progress' => $work_in_progress,
        ];
    }
}
