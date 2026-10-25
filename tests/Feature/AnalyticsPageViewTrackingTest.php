<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\PageView;
use App\Support\Analytics\UserAgent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsPageViewTrackingTest extends TestCase
{
    use RefreshDatabase;

    private const BROWSER_UA = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 14_0) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Safari/605.1.15';

    private const MOBILE_UA = 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1';

    public function test_a_page_view_is_recorded_with_visitor_hash_source_and_device(): void
    {
        $this->withHeaders(['User-Agent' => self::MOBILE_UA, 'Referer' => 'https://l.instagram.com/?u=https%3A%2F%2Fsalento.dev'])
            ->get('/?utm_source=Instagram&utm_medium=social&utm_campaign=meetup-ottobre')
            ->assertOk();

        $view = PageView::query()->sole();

        $this->assertSame('/', $view->path);
        $this->assertMatchesRegularExpression('/^[a-f0-9]{16}$/', $view->visitor_hash);
        $this->assertSame('instagram.com', $view->referrer_host);
        $this->assertSame('instagram', $view->utm_source);
        $this->assertSame('social', $view->utm_medium);
        $this->assertSame('meetup-ottobre', $view->utm_campaign);
        $this->assertSame(UserAgent::DEVICE_MOBILE, $view->device);
    }

    public function test_tracking_does_not_store_anything_in_the_session(): void
    {
        $this->withHeaders(['User-Agent' => self::BROWSER_UA])
            ->get('/')
            ->assertOk()
            ->assertSessionMissing('pv_seen');
    }

    public function test_the_same_visitor_reloading_a_page_is_counted_once(): void
    {
        $this->withHeaders(['User-Agent' => self::BROWSER_UA]);

        $this->get('/')->assertOk();
        $this->get('/')->assertOk();
        $this->get('/events')->assertOk();

        $this->assertSame(1, PageView::query()->where('path', '/')->count());
        $this->assertSame(1, PageView::query()->where('path', '/events')->count());
        $this->assertSame(1, PageView::query()->distinct()->count('visitor_hash'));
    }

    public function test_the_same_visitor_is_counted_again_after_the_dedup_window(): void
    {
        $this->withHeaders(['User-Agent' => self::BROWSER_UA]);

        $this->get('/')->assertOk();
        $this->travel(31)->minutes();
        $this->get('/')->assertOk();

        $this->assertSame(2, PageView::query()->count());
    }

    public function test_different_user_agents_are_different_visitors(): void
    {
        $this->withHeaders(['User-Agent' => self::BROWSER_UA])->get('/')->assertOk();
        $this->withHeaders(['User-Agent' => self::MOBILE_UA])->get('/')->assertOk();

        $this->assertSame(2, PageView::query()->distinct()->count('visitor_hash'));
    }

    public function test_the_visitor_hash_rotates_every_day(): void
    {
        $this->withHeaders(['User-Agent' => self::BROWSER_UA]);

        $this->get('/')->assertOk();
        $this->travel(1)->days();
        $this->get('/')->assertOk();

        $this->assertSame(2, PageView::query()->distinct()->count('visitor_hash'));
    }

    public function test_internal_navigation_has_no_referrer_host(): void
    {
        $this->withHeaders(['User-Agent' => self::BROWSER_UA, 'Referer' => 'http://localhost/events'])
            ->get('/resources')
            ->assertOk();

        $this->assertNull(PageView::query()->sole()->referrer_host);
    }

    public function test_bots_and_empty_user_agents_are_not_tracked(): void
    {
        $this->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'])->get('/')->assertOk();
        $this->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; AhrefsBot/7.0; +http://ahrefs.com/robot/)'])->get('/')->assertOk();
        $this->withHeaders(['User-Agent' => ''])->get('/')->assertOk();

        $this->assertSame(0, PageView::query()->count());
    }

    public function test_event_views_are_linked_to_the_event(): void
    {
        $event = Event::factory()->create(['is_published' => true, 'work_in_progress' => false]);

        $this->withHeaders(['User-Agent' => self::BROWSER_UA])
            ->get(route('events.show', $event))
            ->assertOk();

        $view = PageView::query()->sole();

        $this->assertTrue($view->viewable->is($event));
    }

    public function test_overlong_referrers_are_truncated(): void
    {
        $this->withHeaders(['User-Agent' => self::BROWSER_UA, 'Referer' => 'https://example.com/?q='.str_repeat('a', 600)])
            ->get('/')
            ->assertOk();

        $this->assertSame(255, strlen(PageView::query()->sole()->referrer));
    }
}
