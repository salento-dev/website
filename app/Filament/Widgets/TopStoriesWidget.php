<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasAnalyticsPeriod;
use App\Models\PageView;
use App\Models\Story;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class TopStoriesWidget extends TableWidget
{
    use HasAnalyticsPeriod;

    protected static ?int $sort = 9;

    protected static ?string $heading = 'Storie più visualizzate';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn (): Builder => $this->query()
            )
            ->columns([
                TextColumn::make('rank')
                    ->label('#')
                    ->rowIndex()
                    ->width('48px'),
                TextColumn::make('title')
                    ->label('Storia'),
                TextColumn::make('period_views')
                    ->label('Visite')
                    ->numeric()
                    ->alignEnd(),
                TextColumn::make('period_visitors')
                    ->label('Visitatori')
                    ->numeric()
                    ->alignEnd(),
            ])
            ->emptyStateHeading('Nessuna storia visitata nel periodo')
            ->paginated(false)
            ->striped();
    }

    /**
     * @return Builder<Story>
     */
    private function query(): Builder
    {
        $period = $this->period()->bounds();

        $pageViews = fn (): \Illuminate\Database\Query\Builder => PageView::query()
            ->toBase()
            ->whereColumn('page_views.viewable_id', 'stories.id')
            ->where('page_views.viewable_type', (new Story)->getMorphClass())
            ->whereBetween('page_views.created_at', $period);

        return Story::query()
            ->select('stories.*')
            ->selectSub($pageViews()->selectRaw('COUNT(*)'), 'period_views')
            ->selectSub($pageViews()->selectRaw('COUNT(DISTINCT visitor_hash)'), 'period_visitors')
            ->whereExists($pageViews()->selectRaw('1'))
            ->orderByDesc('period_views')
            ->limit(5);
    }
}
