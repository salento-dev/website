<?php

namespace Database\Seeders;

use App\Models\Resource;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $placeholders = [
            'placeholders/resources/resource-placeholder.jpg',
            'placeholders/resources/resource-placeholder-2.jpg',
            'placeholders/resources/resource-placeholder-3.jpg',
            'placeholders/resources/resource-placeholder-4.jpg',
        ];

        // Create 6 link-based resources
        for ($i = 0; $i < 6; $i++) {
            Resource::factory()
                ->state([
                    'source_type' => 'link',
                    'file_path' => null,
                    'is_published' => true,
                    'image_path' => fake()->randomElement($placeholders),
                ])
                ->create();
        }

        // Create 4 file-based resources
        for ($i = 0; $i < 4; $i++) {
            Resource::factory()
                ->state([
                    'source_type' => 'file',
                    'external_url' => null,
                    'file_path' => 'resources/files/sample.pdf',
                    'is_published' => true,
                    'image_path' => fake()->randomElement($placeholders),
                ])
                ->create();
        }

        // Create 2 unpublished resources
        for ($i = 0; $i < 2; $i++) {
            Resource::factory()
                ->state([
                    'is_published' => false,
                    'image_path' => fake()->randomElement($placeholders),
                ])
                ->create();
        }
    }
}
