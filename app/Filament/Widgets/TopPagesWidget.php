<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasAnalyticsPeriod;
use App\Models\PageView;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TopPagesWidget extends TableWidget
{
    use HasAnalyticsPeriod;

    protected static ?int $sort = 4;

    protected static ?string $heading = 'Pagine più visitate';

    public function getTableRecordKey(Model|array $record): string
    {
        return md5((string) (is_array($record) ? $record['path'] : $record->path));
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn (): Builder => $this->inPeriod(PageView::query())
                    ->selectRaw('path, COUNT(*) as views, COUNT(DISTINCT visitor_hash) as visitors')
                    ->groupBy('path')
                    ->orderByDesc('views')
                    ->limit(10)
            )
            ->defaultKeySort(false)
            ->columns([
                TextColumn::make('rank')
                    ->label('#')
                    ->rowIndex()
                    ->width('48px'),
                TextColumn::make('path')
                    ->label('Pagina'),
                TextColumn::make('views')
                    ->label('Visite')
                    ->numeric()
                    ->alignEnd(),
                TextColumn::make('visitors')
                    ->label('Visitatori')
                    ->numeric()
                    ->alignEnd(),
            ])
            ->emptyStateHeading('Nessuna visita nel periodo')
            ->paginated(false)
            ->striped();
    }
}
