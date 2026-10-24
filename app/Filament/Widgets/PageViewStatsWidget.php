<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasAnalyticsPeriod;
use App\Models\AnalyticsEvent;
use App\Models\PageView;
use App\Support\Analytics\ReportingPeriod;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class PageViewStatsWidget extends StatsOverviewWidget
{
    use HasAnalyticsPeriod;

    protected static ?int $sort = 1;

    /**
     * Four cards in a row only when each one has room for its label; narrower containers use a 2×2 grid.
     *
     * @var array<string, int>
     */
    protected int|array|null $columns = ['@md' => 2, '@3xl' => 4, '!@md' => 2, '!@xl' => 4];

    protected function getStats(): array
    {
        $period = $this->period();
        $current = $this->metrics($period);
        $previous = $this->metrics($period->previous());
        $daily = $this->dailySeries($period);

        return [
            $this->stat('Visite', $current['views'], $previous['views'])
                ->icon(Heroicon::OutlinedEye)
                ->chart($daily['views']),
            $this->stat('Visitatori unici', $current['visitors'], $previous['visitors'])
                ->icon(Heroicon::OutlinedUsers)
                ->chart($daily['visitors']),
            $this->stat('Rimbalzo', $current['bounce_rate'], $previous['bounce_rate'], percentage: true, lowerIsBetter: true)
                ->icon(Heroicon::OutlinedArrowUturnLeft)
                ->extraAttributes(['title' => 'Visitatori che hanno visto una sola pagina']),
            $this->stat('Gruppo WhatsApp', $current['whatsapp_rate'], $previous['whatsapp_rate'], percentage: true, detail: number_format($current['whatsapp'], 0, ',', '.').' click')
                ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                ->chart($daily['whatsapp'])
                ->extraAttributes(['title' => 'Percentuale di visitatori che hanno aperto il gruppo WhatsApp, e numero totale di click']),
        ];
    }

    /**
     * @return array{views: int, visitors: int, bounce_rate: float, whatsapp: int, whatsapp_rate: float}
     */
    private function metrics(ReportingPeriod $period): array
    {
        $views = $this->inPeriod(PageView::query(), $period)->count();

        $visitorsQuery = $this->inPeriod(PageView::query(), $period)
            ->whereNotNull('visitor_hash')
            ->select('visitor_hash', DB::raw('COUNT(*) as views'))
            ->groupBy('visitor_hash');

        $visitors = DB::query()->fromSub($visitorsQuery, 'visitors')->count();
        $bounced = DB::query()->fromSub($visitorsQuery, 'visitors')->where('views', 1)->count();

        $whatsappVisitors = $this->inPeriod(AnalyticsEvent::query(), $period)
            ->where('name', AnalyticsEvent::WHATSAPP)
            ->whereNotNull('visitor_hash')
            ->distinct()
            ->count('visitor_hash');

        return [
            'views' => $views,
            'visitors' => $visitors,
            'bounce_rate' => $visitors > 0 ? round($bounced / $visitors * 100, 1) : 0.0,
            'whatsapp' => $this->inPeriod(AnalyticsEvent::query(), $period)->where('name', AnalyticsEvent::WHATSAPP)->count(),
            'whatsapp_rate' => $visitors > 0 ? round($whatsappVisitors / $visitors * 100, 1) : 0.0,
        ];
    }

    /**
     * @return array{views: list<int>, visitors: list<int>, whatsapp: list<int>}
     */
    private function dailySeries(ReportingPeriod $period): array
    {
        $views = $this->inPeriod(PageView::query(), $period)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as views, COUNT(DISTINCT visitor_hash) as visitors')
            ->groupBy('date')
            ->toBase()
            ->get()
            ->keyBy('date');

        $whatsapp = $this->inPeriod(AnalyticsEvent::query(), $period)
            ->where('name', AnalyticsEvent::WHATSAPP)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as clicks')
            ->groupBy('date')
            ->pluck('clicks', 'date');

        $days = collect(CarbonPeriod::create($period->start, '1 day', $period->end->startOfDay()))
            ->map(fn (mixed $day): string => CarbonImmutable::instance($day)->toDateString());

        return [
            'views' => $days->map(fn (string $day): int => (int) ($views[$day]->views ?? 0))->values()->all(),
            'visitors' => $days->map(fn (string $day): int => (int) ($views[$day]->visitors ?? 0))->values()->all(),
            'whatsapp' => $days->map(fn (string $day): int => (int) ($whatsapp[$day] ?? 0))->values()->all(),
        ];
    }

    private function stat(string $label, int|float $current, int|float $previous, bool $percentage = false, bool $lowerIsBetter = false, ?string $detail = null): Stat
    {
        $prefix = $detail !== null ? $detail.' · ' : '';

        $value = $percentage
            ? number_format((float) $current, 1, ',', '.').'%'
            : number_format((int) $current, 0, ',', '.');

        $stat = Stat::make($label, $value);

        if ($previous == 0) {
            return $stat->description($prefix.($current == 0 ? 'Nessun dato' : 'Nuovo'))->color('gray');
        }

        $delta = $percentage ? $current - $previous : ($current - $previous) / $previous * 100;

        if (round($delta, 1) == 0.0) {
            return $stat->description($prefix.'Stabile')->color('gray');
        }

        $isImprovement = $lowerIsBetter ? $delta < 0 : $delta > 0;

        return $stat
            ->description($prefix.($delta > 0 ? '+' : '').number_format($delta, 1, ',', '.').($percentage ? ' pt' : '%'))
            ->descriptionIcon($delta > 0 ? Heroicon::ArrowTrendingUp : Heroicon::ArrowTrendingDown)
            ->color($isImprovement ? 'success' : 'danger');
    }
}
