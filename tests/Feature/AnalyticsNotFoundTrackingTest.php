<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\NotFoundHit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsNotFoundTrackingTest extends TestCase
{
    use RefreshDatabase;

    private const BROWSER_UA = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 14_0) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Safari/605.1.15';

    public function test_missing_pages_are_counted_with_their_last_referrer(): void
    {
        $this->withHeaders(['User-Agent' => self::BROWSER_UA])->get('/eventi')->assertNotFound();
        $this->withHeaders(['User-Agent' => self::BROWSER_UA, 'Referer' => 'https://www.instagram.com/salento.dev/'])->get('/eventi')->assertNotFound();
        $this->withHeaders(['User-Agent' => self::BROWSER_UA])->get('/eventi')->assertNotFound();

        $hit = NotFoundHit::query()->sole();

        $this->assertSame('/eventi', $hit->path);
        $this->assertSame(3, $hit->hits);
        $this->assertSame('https://www.instagram.com/salento.dev/', $hit->last_referrer);
    }

    public function test_missing_models_are_counted(): void
    {
        $this->withHeaders(['User-Agent' => self::BROWSER_UA])->get('/events/non-esiste')->assertNotFound();

        $this->assertSame('/events/non-esiste', NotFoundHit::query()->sole()->path);
    }

    public function test_work_in_progress_events_are_counted_as_not_found(): void
    {
        $event = Event::factory()->create(['is_published' => true, 'work_in_progress' => true]);

        $this->withHeaders(['User-Agent' => self::BROWSER_UA])->get(route('events.show', $event))->assertNotFound();

        $this->assertSame(1, NotFoundHit::query()->count());
    }

    public function test_scanner_paths_assets_and_bots_are_ignored(): void
    {
        $this->withHeaders(['User-Agent' => self::BROWSER_UA])->get('/wp-login.php')->assertNotFound();
        $this->withHeaders(['User-Agent' => self::BROWSER_UA])->get('/.env')->assertNotFound();
        $this->withHeaders(['User-Agent' => self::BROWSER_UA])->get('/favicon-old.ico')->assertNotFound();
        $this->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'])->get('/eventi')->assertNotFound();

        $this->assertSame(0, NotFoundHit::query()->count());
    }

    public function test_successful_pages_are_not_counted(): void
    {
        $this->withHeaders(['User-Agent' => self::BROWSER_UA])->get('/')->assertOk();

        $this->assertSame(0, NotFoundHit::query()->count());
    }
}
