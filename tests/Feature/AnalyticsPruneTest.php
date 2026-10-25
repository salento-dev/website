<?php

namespace Tests\Feature;

use App\Models\AnalyticsDailyStat;
use App\Models\AnalyticsEvent;
use App\Models\Event;
use App\Models\NotFoundHit;
use App\Models\PageView;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsPruneTest extends TestCase
{
    use RefreshDatabase;

    public function test_old_page_views_are_rolled_up_and_deleted(): void
    {
        $event = Event::factory()->create(['slug' => 'meetup-0']);
        $oldDay = now()->subMonths(13)->setTime(10, 0);

        PageView::factory()->viewing($event, '/events/meetup-0')->count(2)->create(['created_at' => $oldDay, 'visitor_hash' => 'aaaaaaaaaaaaaaaa']);
        PageView::factory()->viewing($event, '/events/meetup-0')->create(['created_at' => $oldDay, 'visitor_hash' => 'bbbbbbbbbbbbbbbb']);
        PageView::factory()->viewing($event, '/events/meetup-0')->create(['created_at' => now()->subMonth()]);

        $this->artisan('analytics:prune')->assertSuccessful();

        $this->assertSame(1, PageView::query()->count());

        $stat = AnalyticsDailyStat::query()->sole();

        $this->assertSame(AnalyticsDailyStat::METRIC_VIEW, $stat->metric);
        $this->assertSame($oldDay->toDateString(), $stat->date->toDateString());
        $this->assertSame(3, $stat->count);
        $this->assertSame(2, $stat->visitors);
        $this->assertTrue($stat->viewable->is($event));
    }

    public function test_event_view_counter_includes_archived_views(): void
    {
        $event = Event::factory()->create(['slug' => 'meetup-0', 'is_published' => true, 'work_in_progress' => false]);

        PageView::factory()->viewing($event, '/events/meetup-0')->count(4)->create(['created_at' => now()->subMonths(14)]);
        PageView::factory()->viewing($event, '/events/meetup-0')->count(2)->create();

        $this->artisan('analytics:prune')->assertSuccessful();

        $this->assertSame(6, $event->loadTotalPageViews()->totalPageViews());

        $this->get(route('events.show', $event))->assertOk()->assertSee('6 visite');
    }

    public function test_old_clicks_are_rolled_up_by_name(): void
    {
        AnalyticsEvent::factory()->whatsapp()->count(3)->create(['created_at' => now()->subMonths(13)]);
        AnalyticsEvent::factory()->whatsapp()->create();

        $this->artisan('analytics:prune')->assertSuccessful();

        $this->assertSame(1, AnalyticsEvent::query()->count());
        $this->assertSame(3, AnalyticsDailyStat::query()->where('metric', AnalyticsEvent::WHATSAPP)->sole()->count);
    }

    public function test_running_twice_adds_to_existing_daily_stats(): void
    {
        $oldDay = now()->subMonths(13);

        PageView::factory()->create(['path' => '/', 'created_at' => $oldDay]);
        $this->artisan('analytics:prune')->assertSuccessful();

        PageView::factory()->create(['path' => '/', 'created_at' => $oldDay]);
        $this->artisan('analytics:prune')->assertSuccessful();

        $this->assertSame(2, AnalyticsDailyStat::query()->sole()->count);
    }

    public function test_stale_not_found_paths_are_deleted(): void
    {
        NotFoundHit::query()->create(['path' => '/vecchio', 'hits' => 3, 'first_seen_at' => now()->subYears(2), 'last_seen_at' => now()->subMonths(13)]);
        NotFoundHit::query()->create(['path' => '/recente', 'hits' => 1, 'first_seen_at' => now(), 'last_seen_at' => now()]);

        $this->artisan('analytics:prune')->assertSuccessful();

        $this->assertSame(['/recente'], NotFoundHit::query()->pluck('path')->all());
    }
}
