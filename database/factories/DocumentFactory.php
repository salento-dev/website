<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);
        $sourceType = fake()->randomElement(['link', 'file']);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100, 999),
            'description' => fake()->paragraphs(2, true),
            'image_path' => null,
            'source_type' => $sourceType,
            'external_url' => $sourceType === 'link' ? fake()->url() : null,
            'file_path' => $sourceType === 'file' ? 'documents/files/sample.pdf' : null,
            'is_published' => fake()->boolean(85),
        ];
    }
}
