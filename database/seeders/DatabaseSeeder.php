<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RealAuthorsSeeder::class,
            EventCategorySeeder::class,
            RealPartnersSeeder::class,
            RealEventsSeeder::class,
            // ResourceSeeder::class,
            StoryCategorySeeder::class,
            RealStoriesSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
