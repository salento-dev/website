<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasAnalyticsPeriod;
use App\Models\PageView;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;

class PageViewsChartWidget extends ChartWidget
{
    use HasAnalyticsPeriod;

    protected static ?int $sort = 2;

    protected ?string $heading = 'Andamento delle visite';

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $period = $this->period();

        $rows = $this->inPeriod(PageView::query(), $period)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as views, COUNT(DISTINCT visitor_hash) as visitors')
            ->groupBy('date')
            ->toBase()
            ->get()
            ->keyBy('date');

        $days = collect(CarbonPeriod::create($period->start, '1 day', $period->end->startOfDay()))
            ->map(fn (mixed $day): CarbonImmutable => CarbonImmutable::instance($day));

        return [
            'datasets' => [
                [
                    'label' => 'Visite',
                    'data' => $days->map(fn (CarbonImmutable $day): int => (int) ($rows[$day->toDateString()]->views ?? 0))->values()->all(),
                    'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Visitatori unici',
                    'data' => $days->map(fn (CarbonImmutable $day): int => (int) ($rows[$day->toDateString()]->visitors ?? 0))->values()->all(),
                    'fill' => false,
                    'tension' => 0.3,
                    'borderColor' => '#22d3ee',
                    'backgroundColor' => '#22d3ee',
                ],
            ],
            'labels' => $days->map(fn (CarbonImmutable $day): string => $day->format('d/m'))->values()->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
