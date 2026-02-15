<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::factory()
            ->count(8)
            ->state([
                'is_published' => true,
                'image_path' => 'placeholders/events/event-placeholder.jpg',
            ])
            ->create();

        Event::factory()
            ->count(4)
            ->state([
                'is_published' => false,
                'image_path' => 'placeholders/events/event-placeholder.jpg',
            ])
            ->create();
    }
}
