<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Partner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $placeholders = [
            'placeholders/events/event-placeholder.jpg',
            'placeholders/events/event-placeholder-2.jpg',
            'placeholders/events/event-placeholder-3.jpg',
            'placeholders/events/event-placeholder-4.jpg',
        ];

        $publishedEvents = collect();
        for ($i = 0; $i < 20; $i++) {
            $publishedEvents->push(
                Event::factory()
                    ->state([
                        'is_published' => true,
                        'image_path' => fake()->randomElement($placeholders),
                    ])
                    ->create()
            );
        }

        $unpublishedEvents = collect();
        for ($i = 0; $i < 4; $i++) {
            $unpublishedEvents->push(
                Event::factory()
                    ->state([
                        'is_published' => false,
                        'image_path' => fake()->randomElement($placeholders),
                    ])
                    ->create()
            );
        }

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

        if (! Schema::hasTable('event_partner')) {
            return;
        }

        $partnerIds = Partner::query()->where('is_published', true)->pluck('id');

        if ($partnerIds->isEmpty()) {
            return;
        }

        $allEvents = $publishedEvents->merge($unpublishedEvents);

        $allEvents->each(function (Event $event) use ($partnerIds): void {
            $event->partners()->sync(
                $partnerIds->random(random_int(1, min(3, $partnerIds->count())))->values()->all(),
            );
        });
    }
}
