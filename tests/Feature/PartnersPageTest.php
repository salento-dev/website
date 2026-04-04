<?php

namespace Tests\Feature;

use App\Models\Partner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnersPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_partners_page_loads_successfully(): void
    {
        $response = $this->get(route('partners'));

        $response->assertStatus(200);
    }

    public function test_partners_page_displays_published_partners(): void
    {
        $publishedPartner = Partner::factory()->create(['is_published' => true]);
        $unpublishedPartner = Partner::factory()->create(['is_published' => false]);

        $response = $this->get(route('partners'));

        $response->assertStatus(200)
            ->assertSee($publishedPartner->name)
            ->assertDontSee($unpublishedPartner->name);
    }
}
