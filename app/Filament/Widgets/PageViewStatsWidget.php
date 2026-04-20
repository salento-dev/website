<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\PageView;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class PageViewStatsWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Visite al sito';

    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $now = Carbon::now();

        return [
            Stat::make('Oggi', PageView::query()->whereDate('created_at', $now->toDateString())->count()),
            Stat::make('Questa settimana', PageView::query()->where('created_at', '>=', $now->copy()->startOfWeek())->count()),
            Stat::make('Questo mese', PageView::query()->where('created_at', '>=', $now->copy()->startOfMonth())->count()),
            Stat::make('Totale', PageView::query()->count()),
        ];
    }
}
