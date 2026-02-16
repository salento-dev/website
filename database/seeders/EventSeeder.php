<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $publishedEvents = Event::factory()
            ->count(8)
            ->state([
                'is_published' => true,
                'image_path' => 'placeholders/events/event-placeholder.jpg',
            ])
            ->create();

        $unpublishedEvents = Event::factory()
            ->count(4)
            ->state([
                'is_published' => false,
                'image_path' => 'placeholders/events/event-placeholder.jpg',
            ])
            ->create();

        if (! Schema::hasTable('event_categories') || ! Schema::hasTable('event_category_event')) {
            return;
        }

        $categoryIds = EventCategory::query()->where('is_active', true)->pluck('id');

        if ($categoryIds->isEmpty()) {
            return;
        }

        $publishedEvents->each(function (Event $event) use ($categoryIds): void {
            $event->categories()->sync(
                $categoryIds->random(random_int(1, min(3, $categoryIds->count())))->values()->all(),
            );
        });

        $unpublishedEvents->each(function (Event $event) use ($categoryIds): void {
            $event->categories()->sync(
                $categoryIds->random(random_int(1, min(2, $categoryIds->count())))->values()->all(),
            );
        });
    }
}
