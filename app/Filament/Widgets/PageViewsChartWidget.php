<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\PageView;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class PageViewsChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Visite negli ultimi 30 giorni';

    protected ?string $pollingInterval = '60s';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $days = collect(range(29, 0))->map(fn (int $daysAgo): Carbon => now()->subDays($daysAgo)->startOfDay());

        $counts = PageView::query()
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->selectRaw('DATE(created_at) as date, count(*) as views')
            ->groupBy('date')
            ->pluck('views', 'date');

        return [
            'datasets' => [
                [
                    'label' => 'Visite',
                    'data' => $days->map(fn (Carbon $day): int => (int) ($counts[$day->toDateString()] ?? 0))->values()->toArray(),
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $days->map(fn (Carbon $day): string => $day->format('d/m'))->values()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
