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

    public function test_card_title_is_a_heading_that_names_the_link(): void
    {
        $event = Event::factory()->create(['title' => 'Meetup accessibile', 'work_in_progress' => false]);

        $html = Blade::render('<x-events.event :event="$event" />', ['event' => $event]);

        $titleId = "event-card-{$event->id}-default-title";
        $this->assertMatchesRegularExpression('/<h3 id="'.$titleId.'"[^>]*>\s*Meetup accessibile\s*<\/h3>/', $html);
        $this->assertStringContainsString('aria-labelledby="'.$titleId.' event-card-'.$event->id.'-default-meta"', $html);
        $this->assertStringContainsString('id="event-card-'.$event->id.'-default-meta"', $html);
    }

    public function test_community_event_is_labelled_for_screen_readers(): void
    {
        $communityEvent = Event::factory()->create(['is_community_event' => true]);
        $externalEvent = Event::factory()->create(['is_community_event' => false]);

        $communityHtml = Blade::render('<x-events.event :event="$event" variant="simple" />', ['event' => $communityEvent]);
        $externalHtml = Blade::render('<x-events.event :event="$event" variant="simple" />', ['event' => $externalEvent]);

        $this->assertStringContainsString('<span class="sr-only">Organizzato da Salento.dev</span>', $communityHtml);
        $this->assertStringNotContainsString('Organizzato da Salento.dev', $externalHtml);
    }

    public function test_work_in_progress_card_is_not_a_link(): void
    {
        $event = Event::factory()->create(['work_in_progress' => true, 'starts_at' => now()->addMonth()]);

        $html = Blade::render('<x-events.event :event="$event" />', ['event' => $event]);

        $this->assertStringNotContainsString(route('events.show', $event), $html);
        $this->assertStringContainsString('In pianificazione', $html);
    }

    public function test_plain_excerpt_prefers_excerpt_and_strips_markdown_from_body(): void
    {
        $withExcerpt = Event::factory()->make(['excerpt' => 'AI & Coding a Lecce', 'body' => '## Ignorato']);
        $withBodyOnly = Event::factory()->make(['excerpt' => null, 'body' => "## Programma\n\nTalk su **Laravel** e [Livewire](https://livewire.laravel.com)."]);
        $empty = Event::factory()->make(['excerpt' => '', 'body' => null]);

        $this->assertSame('AI & Coding a Lecce', $withExcerpt->plain_excerpt);
        $this->assertSame('Programma Talk su Laravel e Livewire.', $withBodyOnly->plain_excerpt);
        $this->assertNull($empty->plain_excerpt);
    }

    public function test_card_without_excerpt_and_body_renders_no_empty_paragraph(): void
    {
        $event = Event::factory()->create(['excerpt' => null, 'body' => null]);

        $html = Blade::render('<x-events.event :event="$event" />', ['event' => $event]);

        $this->assertStringNotContainsString('line-clamp-2 text-sm', $html);
    }

    public function test_card_handles_long_title_without_image(): void
    {
        $title = str_repeat('Titolo lunghissimo senza fine ', 8);
        $event = Event::factory()->create(['title' => $title, 'image_path' => null]);

        $html = Blade::render('<x-events.event :event="$event" />', ['event' => $event]);

        $this->assertStringContainsString(e(trim($title)), $html);
        $this->assertStringContainsString('placeholders/events/event-placeholder.jpg', $html);
    }
}
