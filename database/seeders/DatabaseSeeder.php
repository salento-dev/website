<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

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
        ]);

        Artisan::call('make:filament-user', [
            '--name' => 'Admin User',
            '--email' => 'admin@salento.dev',
            '--password' => 'password123',
            '--no-interaction' => true,
        ]);
    }
}
