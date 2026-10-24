<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class EventCardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Fragment of the heroicon map-pin outline, rendered only when the event has a location.
     */
    private const MAP_PIN_PATH = 'M19.5 10.5c0 7.142';

    public function test_event_without_end_date_is_past_only_after_it_starts(): void
    {
        $future = Event::factory()->make(['starts_at' => now()->addDays(3), 'ends_at' => null]);
        $past = Event::factory()->make(['starts_at' => now()->subDays(3), 'ends_at' => null]);

        $this->assertFalse($future->isPast());
        $this->assertTrue($past->isPast());
    }

    public function test_event_with_end_date_is_past_after_it_ends(): void
    {
        $ongoing = Event::factory()->make(['starts_at' => now()->subHour(), 'ends_at' => now()->addHour()]);
        $ended = Event::factory()->make(['starts_at' => now()->subHours(3), 'ends_at' => now()->subHour()]);

        $this->assertFalse($ongoing->isPast());
        $this->assertTrue($ended->isPast());
    }

    public function test_location_town_is_the_last_segment_of_the_location(): void
    {
        $this->assertSame('Taurisano', Event::factory()->make(['location' => 'Normal, Taurisano'])->location_town);
        $this->assertSame('Lecce', Event::factory()->make(['location' => 'Officine Cantelmo - Mediateca, Lecce'])->location_town);
        $this->assertSame('Galatina', Event::factory()->make(['location' => 'Galatina'])->location_town);
        $this->assertNull(Event::factory()->make(['location' => null])->location_town);
        $this->assertNull(Event::factory()->make(['location' => '  '])->location_town);
    }

    public function test_events_page_card_shows_absolute_date_and_town(): void
    {
        Event::factory()->create([
            'title' => 'Meetup di prova',
            'location' => 'Normal, Taurisano',
            'starts_at' => '2025-11-13 18:30:00',
            'ends_at' => null,
            'is_published' => true,
            'work_in_progress' => false,
        ]);

        $this->get(route('events'))
            ->assertOk()
            ->assertSee('13 nov 2025')
            ->assertSee('datetime="2025-11-13T19:30:00+01:00"', false)
            ->assertSee('Taurisano')
            ->assertDontSee('Normal, Taurisano');
    }

    public function test_home_simple_card_shows_absolute_date_and_town(): void
    {
        Event::factory()->create([
            'title' => 'Lightning di prova',
            'location' => 'Convivya Trattoria & Cocktail Bar, Ruffano',
            'starts_at' => '2025-12-29 18:30:00',
            'ends_at' => '2025-12-29 22:00:00',
            'is_published' => true,
            'work_in_progress' => false,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Lightning di prova')
            ->assertSee('29 dic 2025')
            ->assertSee('Ruffano');
    }

    public function test_card_without_location_shows_only_the_date(): void
    {
        $withLocation = Event::factory()->create(['location' => 'Normal, Taurisano', 'starts_at' => '2026-04-02 09:29:00']);
        $withoutLocation = Event::factory()->create(['location' => null, 'starts_at' => '2026-04-02 09:29:00']);

        $withLocationHtml = Blade::render('<x-events.event :event="$event" />', ['event' => $withLocation]);
        $withoutLocationHtml = Blade::render('<x-events.event :event="$event" />', ['event' => $withoutLocation]);

        $this->assertStringContainsString(self::MAP_PIN_PATH, $withLocationHtml);
        $this->assertStringContainsString('2 apr 2026', $withoutLocationHtml);
        $this->assertStringNotContainsString(self::MAP_PIN_PATH, $withoutLocationHtml);
    }
}
