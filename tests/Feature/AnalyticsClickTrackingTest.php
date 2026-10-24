<?php

namespace Tests\Feature;

use App\Models\AnalyticsEvent;
use App\Models\Event;
use App\Models\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AnalyticsClickTrackingTest extends TestCase
{
    use RefreshDatabase;

    private const BROWSER_UA = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 14_0) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Safari/605.1.15';

    public function test_the_layout_exposes_the_tracking_endpoint(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('<meta name="analytics-endpoint" content="'.route('analytics.events.store').'">', false);
    }

    public function test_a_whatsapp_click_is_classified_from_the_target_url_without_csrf_token(): void
    {
        $this->withHeaders(['User-Agent' => self::BROWSER_UA])
            ->post(route('analytics.events.store'), [
                'url' => 'https://chat.whatsapp.com/Hj9JyfVHUOE2g7dqoql1OB',
                'path' => '/',
            ])
            ->assertNoContent();

        $click = AnalyticsEvent::query()->sole();

        $this->assertSame(AnalyticsEvent::WHATSAPP, $click->name);
        $this->assertSame('chat.whatsapp.com', $click->target_host);
        $this->assertMatchesRegularExpression('/^[a-f0-9]{16}$/', $click->visitor_hash);
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function targetProvider(): array
    {
        return [
            'instagram' => ['https://www.instagram.com/salento.dev/', AnalyticsEvent::INSTAGRAM],
            'linkedin' => ['https://it.linkedin.com/company/salento-dev', AnalyticsEvent::LINKEDIN],
            'email' => ['mailto:salento.dev@gmail.com', AnalyticsEvent::EMAIL],
            'other site' => ['https://laravel.com/docs', AnalyticsEvent::OUTBOUND],
        ];
    }

    #[DataProvider('targetProvider')]
    public function test_clicks_are_classified_by_destination(string $url, string $expected): void
    {
        $this->withHeaders(['User-Agent' => self::BROWSER_UA])
            ->post(route('analytics.events.store'), ['url' => $url, 'path' => '/partners'])
            ->assertNoContent();

        $this->assertSame($expected, AnalyticsEvent::query()->sole()->name);
    }

    public function test_an_explicit_name_and_label_are_kept(): void
    {
        $this->withHeaders(['User-Agent' => self::BROWSER_UA])
            ->post(route('analytics.events.store'), [
                'name' => AnalyticsEvent::RESOURCE,
                'url' => 'http://localhost/storage/slides.pdf',
                'path' => '/resources',
                'label' => 'Slide del meetup',
            ])
            ->assertNoContent();

        $click = AnalyticsEvent::query()->sole();

        $this->assertSame(AnalyticsEvent::RESOURCE, $click->name);
        $this->assertSame('Slide del meetup', $click->label);
    }

    public function test_clicks_on_an_event_page_are_linked_to_the_event(): void
    {
        $event = Event::factory()->create(['slug' => 'meetup-0-taurisano']);

        $this->withHeaders(['User-Agent' => self::BROWSER_UA])
            ->post(route('analytics.events.store'), [
                'url' => 'https://chat.whatsapp.com/Hj9JyfVHUOE2g7dqoql1OB',
                'path' => '/events/meetup-0-taurisano',
            ])
            ->assertNoContent();

        $this->assertTrue(AnalyticsEvent::query()->sole()->viewable->is($event));
    }

    public function test_invalid_payloads_are_rejected(): void
    {
        $this->withHeaders(['User-Agent' => self::BROWSER_UA, 'Accept' => 'application/json'])
            ->post(route('analytics.events.store'), [
                'name' => 'purchase',
                'path' => 'https://evil.example',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'url', 'path']);

        $this->assertSame(0, AnalyticsEvent::query()->count());
    }

    public function test_bot_clicks_are_ignored(): void
    {
        $this->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)'])
            ->post(route('analytics.events.store'), ['url' => 'https://laravel.com', 'path' => '/'])
            ->assertNoContent();

        $this->assertSame(0, AnalyticsEvent::query()->count());
    }

    public function test_resource_links_are_marked_for_tracking(): void
    {
        Resource::factory()->create([
            'title' => 'Diagram as code',
            'source_type' => 'link',
            'external_url' => 'https://example.com/diagram',
            'is_published' => true,
        ]);

        $this->get(route('resources'))
            ->assertOk()
            ->assertSee('data-track="resource"', false)
            ->assertSee('data-track-label="Diagram as code"', false);
    }
}
