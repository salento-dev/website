<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasAnalyticsPeriod;
use App\Models\AnalyticsEvent;
use App\Models\Event;
use App\Models\PageView;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TopEventsWidget extends TableWidget
{
    use HasAnalyticsPeriod;

    protected static ?int $sort = 8;

    protected static ?string $heading = 'Eventi più visualizzati';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->description('Visite e click nel periodo. "Prima dell\'evento" conta tutte le visite arrivate prima della data di inizio.')
            ->query(fn (): Builder => $this->query())
            ->defaultKeySort(false)
            ->columns([
                TextColumn::make('rank')
                    ->label('#')
                    ->rowIndex()
                    ->width('48px'),
                TextColumn::make('title')
                    ->label('Evento')
                    ->description(fn (Event $record): string => (string) $record->starts_at?->timezone('Europe/Rome')->format('d/m/Y')),
                TextColumn::make('period_views')
                    ->label('Visite')
                    ->numeric()
                    ->alignEnd(),
                TextColumn::make('period_visitors')
                    ->label('Visitatori')
                    ->numeric()
                    ->alignEnd(),
                TextColumn::make('pre_event_views')
                    ->label('Prima dell\'evento')
                    ->numeric()
                    ->alignEnd(),
                TextColumn::make('period_clicks')
                    ->label('Click in uscita')
                    ->numeric()
                    ->alignEnd(),
                TextColumn::make('conversion')
                    ->label('Conversione')
                    ->state(fn (Event $record): string => $record->period_visitors > 0
                        ? number_format($record->period_click_visitors / $record->period_visitors * 100, 1, ',', '.').'%'
                        : '—')
                    ->tooltip('Visitatori che hanno cliccato un link in uscita (WhatsApp, social, link esterni) sul totale dei visitatori.')
                    ->alignEnd(),
            ])
            ->emptyStateHeading('Nessun evento visitato nel periodo')
            ->paginated(false)
            ->striped();
    }

    /**
     * @return Builder<Event>
     */
    private function query(): Builder
    {
        $period = $this->period()->bounds();
        $morphClass = (new Event)->getMorphClass();

        $pageViews = fn (): \Illuminate\Database\Query\Builder => PageView::query()
            ->toBase()
            ->whereColumn('page_views.viewable_id', 'events.id')
            ->where('page_views.viewable_type', $morphClass);

        $clicks = fn (): \Illuminate\Database\Query\Builder => AnalyticsEvent::query()
            ->toBase()
            ->whereColumn('analytics_events.viewable_id', 'events.id')
            ->where('analytics_events.viewable_type', $morphClass)
            ->whereBetween('analytics_events.created_at', $period);

        return Event::query()
            ->select('events.*')
            ->selectSub($pageViews()->whereBetween('page_views.created_at', $period)->selectRaw('COUNT(*)'), 'period_views')
            ->selectSub($pageViews()->whereBetween('page_views.created_at', $period)->selectRaw('COUNT(DISTINCT visitor_hash)'), 'period_visitors')
            ->selectSub($pageViews()->whereColumn('page_views.created_at', '<', 'events.starts_at')->selectRaw('COUNT(*)'), 'pre_event_views')
            ->selectSub($clicks()->selectRaw('COUNT(*)'), 'period_clicks')
            ->selectSub($clicks()->selectRaw('COUNT(DISTINCT visitor_hash)'), 'period_click_visitors')
            ->whereExists($pageViews()->whereBetween('page_views.created_at', $period)->select(DB::raw(1)))
            ->orderByDesc('period_views')
            ->limit(10);
    }
}
