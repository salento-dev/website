<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resource>
 */
class ResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sourceType = fake()->randomElement(['link', 'file']);

        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraphs(2, true),
            'source_type' => $sourceType,
            'external_url' => $sourceType === 'link' ? fake()->url() : null,
            'file_path' => $sourceType === 'file' ? 'resources/files/sample.pdf' : null,
            'is_published' => fake()->boolean(85),
        ];
    }
}
