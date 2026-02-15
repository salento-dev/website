<?php

namespace Database\Seeders;

use App\Models\DocumentSlide;
use Illuminate\Database\Seeder;

class DocumentSlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DocumentSlide::factory()
            ->count(6)
            ->state([
                'source_type' => 'link',
                'file_path' => null,
                'is_published' => true,
                'image_path' => 'placeholders/document-slides/document-placeholder.jpg',
            ])
            ->create();

        DocumentSlide::factory()
            ->count(4)
            ->state([
                'source_type' => 'file',
                'external_url' => null,
                'file_path' => 'document-slides/files/sample.pdf',
                'is_published' => true,
                'image_path' => 'placeholders/document-slides/document-placeholder.jpg',
            ])
            ->create();

        DocumentSlide::factory()
            ->count(2)
            ->state([
                'is_published' => false,
                'image_path' => 'placeholders/document-slides/document-placeholder.jpg',
            ])
            ->create();
    }
}
