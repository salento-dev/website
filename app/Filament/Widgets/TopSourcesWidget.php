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

class TopSourcesWidget extends TableWidget
{
    use HasAnalyticsPeriod;

    protected static ?int $sort = 5;

    protected static ?string $heading = 'Sorgenti di traffico';

    public function getTableRecordKey(Model|array $record): string
    {
        return md5((string) (is_array($record) ? $record['source'] : $record->source));
    }

    public function table(Table $table): Table
    {
        return $table
            ->description('Parametro utm_source se presente, altrimenti il sito di provenienza.')
            ->query(
                fn (): Builder => $this->inPeriod(PageView::query())
                    ->selectRaw('COALESCE(utm_source, referrer_host) as source, COUNT(*) as views, COUNT(DISTINCT visitor_hash) as visitors')
                    ->groupBy('source')
                    ->orderByDesc('views')
                    ->limit(10)
            )
            ->defaultKeySort(false)
            ->columns([
                TextColumn::make('rank')
                    ->label('#')
                    ->rowIndex()
                    ->width('48px'),
                TextColumn::make('source')
                    ->label('Sorgente')
                    ->placeholder('Diretto / sconosciuto'),
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
