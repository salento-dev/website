<?php

namespace Tests\Feature;

use App\Livewire\ResourcesPage;
use App\Models\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ResourcesPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_empty_search_shows_search_specific_message(): void
    {
        Resource::factory()->create(['title' => 'Diagram as code', 'is_published' => true]);

        Livewire::test(ResourcesPage::class)
            ->set('search', 'kubernetes')
            ->assertSee('Nessuna risorsa trovata per “kubernetes”.')
            ->assertDontSee('Nessuna risorsa pubblicata al momento.');
    }

    public function test_no_resources_shows_generic_empty_message(): void
    {
        Livewire::test(ResourcesPage::class)
            ->assertSee('Nessuna risorsa pubblicata al momento.');
    }
}
