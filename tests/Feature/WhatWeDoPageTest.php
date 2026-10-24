<?php

namespace Tests\Feature;

use Tests\TestCase;

class WhatWeDoPageTest extends TestCase
{
    public function test_what_we_do_page_loads_with_site_layout(): void
    {
        $response = $this->get(route('services'));

        $response->assertStatus(200)
            ->assertSee('<title>Cosa facciamo - ', false)
            ->assertSee('Vai al contenuto')
            ->assertSee('Le Nostre Attività')
            ->assertSee('Codice di Condotta');
    }
}
