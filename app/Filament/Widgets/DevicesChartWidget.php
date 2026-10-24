<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasAnalyticsPeriod;
use App\Models\PageView;
use App\Support\Analytics\UserAgent;
use Filament\Widgets\ChartWidget;

class DevicesChartWidget extends ChartWidget
{
    use HasAnalyticsPeriod;

    protected static ?int $sort = 7;

    protected ?string $heading = 'Dispositivi';

    protected ?string $maxHeight = '260px';

    protected function getData(): array
    {
        $counts = $this->inPeriod(PageView::query())
            ->whereNotNull('device')
            ->selectRaw('device, COUNT(*) as views')
            ->groupBy('device')
            ->pluck('views', 'device');

        return [
            'datasets' => [
                [
                    'label' => 'Visite',
                    'data' => collect(UserAgent::DEVICE_LABELS)->keys()->map(fn (string $device): int => (int) ($counts[$device] ?? 0))->all(),
                    'backgroundColor' => ['#22d3ee', '#a78bfa', '#94a3b8'],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => array_values(UserAgent::DEVICE_LABELS),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => ['display' => false],
                'y' => ['display' => false],
            ],
        ];
    }
}
