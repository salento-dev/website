<?php

namespace Database\Seeders;

use App\Models\StoryCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StoryCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Esperimento',
            'Tutorial',
            'Riflessione',
            'Case Study',
            'Open Source',
            'DevOps',
            'Frontend',
            'Backend',
        ];

        foreach ($categories as $index => $name) {
            StoryCategory::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
            );
        }
    }
}
