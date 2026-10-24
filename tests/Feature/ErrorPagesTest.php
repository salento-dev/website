<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErrorPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_unknown_event_slug_shows_the_italian_not_found_page(): void
    {
        $this->get('/events/evento-che-non-esiste')
            ->assertNotFound()
            ->assertSee('lang="it"', false)
            ->assertSee('<title>Pagina non trovata - '.config('app.name').'</title>', false)
            ->assertSee('ERR_404')
            ->assertSee('Pagina non trovata')
            ->assertSee('ancora in preparazione')
            ->assertSee('href="'.route('events').'"', false)
            ->assertSee('href="'.route('home').'"', false)
            ->assertSee($this->whatsappUrl(), false);
    }

    public function test_unknown_url_outside_the_routes_shows_the_same_page(): void
    {
        $this->get('/pagina-che-non-esiste')
            ->assertNotFound()
            ->assertSee('Pagina non trovata')
            ->assertSee('href="'.route('events').'"', false);
    }

    public function test_work_in_progress_event_detail_shows_the_not_found_page(): void
    {
        $event = Event::factory()->create([
            'starts_at' => now()->addMonth(),
            'ends_at' => null,
            'is_published' => true,
            'work_in_progress' => true,
        ]);

        $this->get(route('events.show', $event))
            ->assertNotFound()
            ->assertSee('Pagina non trovata')
            ->assertSee('href="'.route('events').'"', false)
            ->assertDontSee($event->title);
    }

    public function test_unpublished_event_detail_shows_the_not_found_page(): void
    {
        $event = Event::factory()->create([
            'starts_at' => now()->subMonth(),
            'ends_at' => null,
            'is_published' => false,
            'work_in_progress' => false,
        ]);

        $this->get(route('events.show', $event))
            ->assertNotFound()
            ->assertSee('Pagina non trovata')
            ->assertSee('href="'.route('events').'"', false)
            ->assertDontSee($event->title);
    }

    public function test_server_error_page_renders_with_the_site_shell(): void
    {
        $html = view('errors.500')->render();

        $this->assertStringContainsString('ERR_500', $html);
        $this->assertStringContainsString('Qualcosa è andato storto', $html);
        $this->assertStringContainsString('href="'.route('home').'"', $html);
        $this->assertStringContainsString($this->whatsappUrl(), $html);
    }

    public function test_maintenance_page_renders_with_the_site_shell(): void
    {
        $html = view('errors.503')->render();

        $this->assertStringContainsString('ERR_503', $html);
        $this->assertStringContainsString('Torniamo subito', $html);
        $this->assertStringContainsString($this->whatsappUrl(), $html);
    }

    private function whatsappUrl(): string
    {
        return collect(config('community.social'))->firstWhere('key', 'whatsapp')['url'];
    }
}
