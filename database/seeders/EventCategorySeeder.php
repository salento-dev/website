<?php

namespace Database\Seeders;

use App\Models\EventCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Meet-up', 'description' => 'Incontri tecnici periodici della community.'],
            ['name' => 'Live Coding', 'description' => 'Sessioni pratiche e coding dal vivo.'],
            ['name' => 'Networking', 'description' => 'Eventi di confronto informale e networking professionale.'],
            ['name' => 'Workshop', 'description' => 'Laboratori tecnici verticali su stack specifici.'],
            ['name' => 'Talk', 'description' => 'Talk e approfondimenti su temi sviluppo software.'],
            ['name' => 'Hackathon', 'description' => 'Eventi intensivi di sviluppo e collaborazione.'],
            ['name' => 'Mentorship', 'description' => 'Sessioni di mentoring e orientamento dev.'],
        ];

        foreach ($categories as $index => $category) {
            EventCategory::query()->updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
            );
        }
    }
}
