<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class TopEventsWidget extends TableWidget
{
    protected static ?int $sort = 3;

    protected static ?string $heading = 'Eventi più visualizzati';

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = '60s';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn (): Builder => Event::query()
                    ->withCount('pageViews')
                    ->orderByDesc('page_views_count')
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('rank')
                    ->label('#')
                    ->rowIndex()
                    ->width('48px'),
                TextColumn::make('title')
                    ->label('Evento'),
                TextColumn::make('page_views_count')
                    ->label('Visite')
                    ->numeric()
                    ->alignEnd(),
            ])
            ->paginated(false)
            ->striped();
    }
}
