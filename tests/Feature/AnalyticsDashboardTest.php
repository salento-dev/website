<?php

namespace Tests\Feature;

use App\Filament\Pages\Dashboard;
use App\Filament\Widgets\ClickEventsWidget;
use App\Filament\Widgets\DevicesChartWidget;
use App\Filament\Widgets\NotFoundHitsWidget;
use App\Filament\Widgets\PageViewsChartWidget;
use App\Filament\Widgets\PageViewStatsWidget;
use App\Filament\Widgets\TopCampaignsWidget;
use App\Filament\Widgets\TopEventsWidget;
use App\Filament\Widgets\TopPagesWidget;
use App\Filament\Widgets\TopSourcesWidget;
use App\Filament\Widgets\TopStoriesWidget;
use App\Models\AnalyticsEvent;
use App\Models\Event;
use App\Models\NotFoundHit;
use App\Models\PageView;
use App\Models\Story;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AnalyticsDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_the_dashboard_renders_with_all_analytics_widgets(): void
    {
        $this->seedTraffic();

        $this->get(Dashboard::getUrl())->assertOk();

        foreach ([
            PageViewStatsWidget::class,
            PageViewsChartWidget::class,
            ClickEventsWidget::class,
            TopPagesWidget::class,
            TopSourcesWidget::class,
            TopCampaignsWidget::class,
            DevicesChartWidget::class,
            TopEventsWidget::class,
            TopStoriesWidget::class,
            NotFoundHitsWidget::class,
        ] as $widget) {
            Livewire::test($widget, ['pageFilters' => ['period' => '30']])->assertOk();
        }
    }

    public function test_the_dashboard_groups_widgets_in_tabs_and_shows_the_period(): void
    {
        $this->travelTo(now()->setDate(2026, 10, 25)->setTime(12, 0));
        NotFoundHit::query()->create(['path' => '/eventi', 'hits' => 2, 'first_seen_at' => now(), 'last_seen_at' => now()]);

        $this->get(Dashboard::getUrl())
            ->assertOk()
            ->assertSeeInOrder(['Panoramica', 'Provenienza', 'Contenuti', 'Errori 404'])
            ->assertSee('Dal 26/09/2026 al 25/10/2026 (30 giorni), confrontati con i 30 giorni precedenti.')
            ->assertDontSee('Benvenuto');
    }

    public function test_stats_compare_with_the_previous_period(): void
    {
        PageView::factory()->count(3)->create(['visitor_hash' => 'aaaaaaaaaaaaaaaa']);
        PageView::factory()->create(['visitor_hash' => 'bbbbbbbbbbbbbbbb']);
        PageView::factory()->count(2)->create(['created_at' => now()->subDays(40)]);
        AnalyticsEvent::factory()->whatsapp()->create(['visitor_hash' => 'aaaaaaaaaaaaaaaa']);

        Livewire::test(PageViewStatsWidget::class, ['pageFilters' => ['period' => '30']])
            ->assertSee('Visite')
            ->assertSee('+100,0%')
            ->assertSee('Gruppo WhatsApp')
            ->assertSee('50,0%')
            ->assertSee('1 click');
    }

    public function test_sources_group_utm_and_referrer_hosts(): void
    {
        PageView::factory()->count(2)->create(['referrer_host' => 'instagram.com']);
        PageView::factory()->create(['utm_source' => 'qr-meetup', 'referrer_host' => null]);
        PageView::factory()->create(['referrer_host' => null]);

        Livewire::test(TopSourcesWidget::class, ['pageFilters' => ['period' => '30']])
            ->assertSee('instagram.com')
            ->assertSee('qr-meetup')
            ->assertSee('Diretto / sconosciuto');
    }

    public function test_period_filter_excludes_older_traffic(): void
    {
        PageView::factory()->create(['path' => '/recente']);
        PageView::factory()->create(['path' => '/vecchia', 'created_at' => now()->subDays(20)]);

        Livewire::test(TopPagesWidget::class, ['pageFilters' => ['period' => '7']])
            ->assertSee('/recente')
            ->assertDontSee('/vecchia');
    }

    public function test_top_events_show_pre_event_views_and_conversion(): void
    {
        $event = Event::factory()->create(['title' => 'AperiDev Galatina', 'slug' => 'aperidev', 'starts_at' => now()->addDays(3)]);

        PageView::factory()->viewing($event, '/events/aperidev')->create(['visitor_hash' => 'aaaaaaaaaaaaaaaa']);
        PageView::factory()->viewing($event, '/events/aperidev')->create(['visitor_hash' => 'bbbbbbbbbbbbbbbb']);
        AnalyticsEvent::factory()->whatsapp()->create([
            'path' => '/events/aperidev',
            'viewable_type' => $event->getMorphClass(),
            'viewable_id' => $event->id,
            'visitor_hash' => 'aaaaaaaaaaaaaaaa',
        ]);

        Livewire::test(TopEventsWidget::class, ['pageFilters' => ['period' => '30']])
            ->assertSee('AperiDev Galatina')
            ->assertSee('50,0%');
    }

    public function test_a_not_found_path_can_be_marked_as_resolved(): void
    {
        $hit = NotFoundHit::query()->create(['path' => '/eventi', 'hits' => 4, 'first_seen_at' => now(), 'last_seen_at' => now()]);

        Livewire::test(NotFoundHitsWidget::class)
            ->assertSee('/eventi')
            ->callAction(TestAction::make('resolve')->table($hit));

        $this->assertModelMissing($hit);
    }

    private function seedTraffic(): void
    {
        $event = Event::factory()->create(['slug' => 'meetup']);
        $story = Story::factory()->create();

        PageView::factory()->count(3)->create(['utm_campaign' => 'meetup-ottobre', 'utm_source' => 'instagram']);
        PageView::factory()->viewing($event, '/events/meetup')->create();
        PageView::factory()->viewing($story, '/stories/'.$story->slug)->create();
        AnalyticsEvent::factory()->whatsapp()->create();
        NotFoundHit::query()->create(['path' => '/eventi', 'hits' => 1, 'first_seen_at' => now(), 'last_seen_at' => now()]);
    }
}
