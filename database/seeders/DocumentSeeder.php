<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Document::factory()
            ->count(6)
            ->state([
                'source_type' => 'link',
                'file_path' => null,
                'is_published' => true,
                'image_path' => 'placeholders/documents/document-placeholder.jpg',
            ])
            ->create();

        Document::factory()
            ->count(4)
            ->state([
                'source_type' => 'file',
                'external_url' => null,
                'file_path' => 'documents/files/sample.pdf',
                'is_published' => true,
                'image_path' => 'placeholders/documents/document-placeholder.jpg',
            ])
            ->create();

        Document::factory()
            ->count(2)
            ->state([
                'is_published' => false,
                'image_path' => 'placeholders/documents/document-placeholder.jpg',
            ])
            ->create();
    }
}
