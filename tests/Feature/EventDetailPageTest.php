<?php

namespace Tests\Feature;

use App\Livewire\EventDetailPage;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EventDetailPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_past_event_shows_concluded_status_next_event_and_whatsapp(): void
    {
        $past = $this->publishedEvent(['title' => 'Meetup passato', 'starts_at' => now()->subMonth()]);
        $next = $this->publishedEvent(['title' => 'Lightning Night futura', 'starts_at' => now()->addWeek()]);

        $this->get(route('events.show', $past))
            ->assertOk()
            ->assertSee('Evento concluso')
            ->assertSee('href="'.route('events.show', $next).'"', false)
            ->assertSee('Lightning Night futura')
            ->assertSee($this->whatsappUrl(), false)
            ->assertSee('prima di tutto nel gruppo WhatsApp della community');
    }

    public function test_past_event_without_upcoming_events_still_points_to_whatsapp(): void
    {
        $past = $this->publishedEvent(['starts_at' => now()->subMonth()]);

        $this->get(route('events.show', $past))
            ->assertOk()
            ->assertSee('Evento concluso')
            ->assertSee('Nessun evento in programma, per ora')
            ->assertSee($this->whatsappUrl(), false)
            ->assertSee('prima di tutto nel gruppo WhatsApp della community');
    }

    public function test_past_event_does_not_point_to_work_in_progress_or_unpublished_events(): void
    {
        $past = $this->publishedEvent(['starts_at' => now()->subMonth()]);
        $wip = $this->publishedEvent(['starts_at' => now()->addWeek(), 'work_in_progress' => true]);
        $unpublished = $this->publishedEvent(['starts_at' => now()->addWeek(), 'is_published' => false]);

        $this->get(route('events.show', $past))
            ->assertOk()
            ->assertSee('Nessun evento in programma, per ora')
            ->assertDontSee('href="'.route('events.show', $wip).'"', false)
            ->assertDontSee('href="'.route('events.show', $unpublished).'"', false);
    }

    public function test_upcoming_event_shows_upcoming_status_without_the_past_event_block(): void
    {
        $upcoming = $this->publishedEvent(['starts_at' => now()->addWeek()]);

        $this->get(route('events.show', $upcoming))
            ->assertOk()
            ->assertSee('Prossimo evento')
            ->assertDontSee('Evento concluso')
            ->assertDontSee('past-event-next-title', false)
            ->assertDontSee('prima di tutto nel gruppo WhatsApp della community')
            ->assertDontSee('Iscriviti');
    }

    public function test_event_happening_now_shows_ongoing_status(): void
    {
        $ongoing = $this->publishedEvent([
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addHour(),
        ]);

        $this->get(route('events.show', $ongoing))
            ->assertOk()
            ->assertSee('In corso')
            ->assertDontSee('Evento concluso')
            ->assertDontSee('past-event-next-title', false);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function test_related_events_are_the_most_recent_navigable_ones(): void
    {
        $current = $this->publishedEvent(['starts_at' => now()->subMonths(2)]);
        $oldest = $this->publishedEvent(['starts_at' => now()->subYear()]);
        $recent = $this->publishedEvent(['starts_at' => now()->subWeek()]);
        $upcoming = $this->publishedEvent(['starts_at' => now()->addWeek()]);
        $this->publishedEvent(['starts_at' => now()->addMonth(), 'work_in_progress' => true]);

        Livewire::test(EventDetailPage::class, ['event' => $current])
            ->assertViewHas('relatedEvents', fn ($events): bool => $events->pluck('id')->all() === [$upcoming->id, $recent->id]);

        $this->assertNotContains($oldest->id, [$upcoming->id, $recent->id]);
    }

    private function publishedEvent(array $attributes): Event
    {
        return Event::factory()->create([
            'ends_at' => null,
            'is_published' => true,
            'work_in_progress' => false,
            ...$attributes,
        ]);
    }

    private function whatsappUrl(): string
    {
        return collect(config('community.social'))->firstWhere('key', 'whatsapp')['url'];
    }
}
