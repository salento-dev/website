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
        Resource::factory()
            ->count(6)
            ->state([
                'source_type' => 'link',
                'file_path' => null,
                'is_published' => true,
                'image_path' => 'placeholders/resources/resource-placeholder.jpg',
            ])
            ->create();

        Resource::factory()
            ->count(4)
            ->state([
                'source_type' => 'file',
                'external_url' => null,
                'file_path' => 'resources/files/sample.pdf',
                'is_published' => true,
                'image_path' => 'placeholders/resources/resource-placeholder.jpg',
            ])
            ->create();

        Resource::factory()
            ->count(2)
            ->state([
                'is_published' => false,
                'image_path' => 'placeholders/resources/resource-placeholder.jpg',
            ])
            ->create();
    }
}
