<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
