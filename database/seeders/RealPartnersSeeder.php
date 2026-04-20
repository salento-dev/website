<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class RealPartnersSeeder extends Seeder
{
    private string $sourceDir;

    public function __construct()
    {
        $this->sourceDir = database_path('seeders/partners');
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Partner::create([
            'name' => 'Lecce Digital Community',
            'description' => '',
            'logo_path' => $this->copyImage('lecce-digital-community.webp'),
            'website_url' => 'https://www.instagram.com/lecce_digital_community/',
            'is_published' => true,
        ]);

        Partner::create([
            'name' => 'Azure Meetup Puglia',
            'description' => '',
            'logo_path' => $this->copyImage('azure-meetup-puglia.jpg'),
            'website_url' => 'https://azure-meetup-puglia.github.io/',
            'is_published' => true,
        ]);

        Partner::create([
            'name' => 'GDG Lecce',
            'description' => '',
            'logo_path' => $this->copyImage('gdg-lecce.jpg'),
            'website_url' => 'https://gdg.community.dev/gdg-lecce/',
            'is_published' => true,
        ]);
    }

    private function copyImage(string $filename): string
    {
        $source = $this->sourceDir.DIRECTORY_SEPARATOR.$filename;

        if (! file_exists($source)) {
            return '';
        }

        $destination = 'events/'.$filename;

        Storage::disk('public')->put(
            $destination,
            file_get_contents($source),
        );

        return $destination;
    }
}
