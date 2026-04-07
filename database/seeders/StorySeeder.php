<?php

namespace Database\Seeders;

use App\Models\Story;
use App\Models\StoryCategory;
use Illuminate\Database\Seeder;

class StorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = StoryCategory::all();

        Story::factory(12)->create([
            'story_category_id' => fn () => $categories->random()->id,
            'is_published' => true,
        ]);

        Story::factory(3)->create([
            'story_category_id' => fn () => $categories->random()->id,
            'is_published' => false,
        ]);
    }
}
