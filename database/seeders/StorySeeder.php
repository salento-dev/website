<?php

namespace Database\Seeders;

use App\Models\Author;
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
        $authors = Author::factory(8)->create();

        Story::factory(12)
            ->create([
                'story_category_id' => fn () => $categories->random()->id,
                'is_published' => true,
            ])
            ->each(function (Story $story) use ($authors): void {
                $story->authors()->attach(
                    $authors->random(fake()->numberBetween(1, 2))->pluck('id'),
                );
            });

        Story::factory(3)
            ->create([
                'story_category_id' => fn () => $categories->random()->id,
                'is_published' => false,
            ])
            ->each(function (Story $story) use ($authors): void {
                $story->authors()->attach(
                    $authors->random(1)->pluck('id'),
                );
            });
    }
}
