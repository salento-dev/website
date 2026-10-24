<?php

namespace Tests\Feature;

use App\Livewire\EventsPage;
use App\Livewire\StoriesPage;
use App\Models\Event;
use App\Models\Story;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AccessibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_whatsapp_links_start_with_the_visible_label_and_announce_the_new_tab(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('aria-label="Join WhatsApp – gruppo della community, si apre in una nuova scheda"', false)
            ->assertDontSee('Open WhatsApp group');
    }

    public function test_past_events_search_announces_the_result_count_and_keeps_the_search_field(): void
    {
        Event::factory()->create([
            'title' => 'Lightning Night',
            'is_published' => true,
            'work_in_progress' => false,
            'starts_at' => now()->subMonth(),
        ]);
        Event::factory()->create([
            'title' => 'Meetup #0',
            'is_published' => true,
            'work_in_progress' => false,
            'starts_at' => now()->subMonths(2),
        ]);

        Livewire::test(EventsPage::class)
            ->assertSeeHtml('<span class="sr-only" role="status">2 eventi trovati</span>')
            ->set('search', 'Lightning')
            ->assertSeeHtml('<span class="sr-only" role="status">1 evento trovato</span>')
            ->set('search', 'nessun risultato possibile')
            ->assertSeeHtml('<span class="sr-only" role="status">0 eventi trovati</span>')
            ->assertSee('Cerca nello storico...')
            ->assertSee('Nessun evento trovato con i filtri selezionati.');
    }

    public function test_stories_search_announces_the_result_count(): void
    {
        Story::factory()->create(['title' => 'Duomo in 3D', 'is_published' => true]);
        Story::factory()->create(['title' => 'Kubernetes in locale', 'is_published' => true]);

        Livewire::test(StoriesPage::class)
            ->assertSeeHtml('<span class="sr-only" role="status">2 storie trovate</span>')
            ->set('search', 'Duomo')
            ->assertSeeHtml('<span class="sr-only" role="status">1 storia trovata</span>')
            ->set('search', 'nessun risultato possibile')
            ->assertSeeHtml('<span class="sr-only" role="status">0 storie trovate</span>');
    }
}
