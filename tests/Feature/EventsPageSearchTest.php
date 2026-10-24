<?php

namespace Tests\Feature;

use App\Livewire\EventsPage;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EventsPageSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_matches_past_events_by_title_location_and_text(): void
    {
        $pastEvent = fn (array $attributes): Event => Event::factory()->create([
            'is_published' => true,
            'work_in_progress' => false,
            'starts_at' => now()->subMonth(),
            'ends_at' => now()->subMonth()->addHours(2),
            ...$attributes,
        ]);

        $byTitle = $pastEvent(['title' => 'Lightning Night', 'location' => 'Bar Centrale, Ruffano', 'excerpt' => 'Talk brevi.', 'body' => 'Serata di talk.']);
        $byLocation = $pastEvent(['title' => 'Meetup #0', 'location' => 'Sala Civica, Taurisano', 'excerpt' => 'Primo incontro.', 'body' => 'Presentazioni.']);
        $byBody = $pastEvent(['title' => 'AI & Coding', 'location' => 'Hub, Lecce', 'excerpt' => 'Agenti e IDE.', 'body' => 'Demo con Kubernetes in locale.']);

        Livewire::test(EventsPage::class)
            ->set('search', 'Lightning')
            ->assertSee($byTitle->title)
            ->assertDontSee($byLocation->title)
            ->set('search', 'Taurisano')
            ->assertSee($byLocation->title)
            ->assertDontSee($byTitle->title)
            ->set('search', 'Kubernetes')
            ->assertSee($byBody->title)
            ->assertDontSee($byLocation->title);
    }
}
