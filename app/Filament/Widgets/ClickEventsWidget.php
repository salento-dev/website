<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasAnalyticsPeriod;
use App\Models\AnalyticsEvent;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ClickEventsWidget extends TableWidget
{
    use HasAnalyticsPeriod;

    protected static ?int $sort = 3;

    protected static ?string $heading = 'Click in uscita';

    public function getTableRecordKey(Model|array $record): string
    {
        return (string) (is_array($record) ? $record['name'] : $record->name);
    }

    public function table(Table $table): Table
    {
        return $table
            ->description('Quante volte i visitatori hanno aperto il gruppo WhatsApp, i social, le risorse o altri link esterni.')
            ->query(
                fn (): Builder => $this->inPeriod(AnalyticsEvent::query())
                    ->selectRaw('name, COUNT(*) as clicks, COUNT(DISTINCT visitor_hash) as visitors')
                    ->groupBy('name')
                    ->orderByDesc('clicks')
            )
            ->defaultKeySort(false)
            ->columns([
                TextColumn::make('name')
                    ->label('Destinazione')
                    ->formatStateUsing(fn (string $state): string => AnalyticsEvent::labelFor($state)),
                TextColumn::make('clicks')
                    ->label('Click')
                    ->numeric()
                    ->alignEnd(),
                TextColumn::make('visitors')
                    ->label('Visitatori')
                    ->numeric()
                    ->alignEnd(),
            ])
            ->emptyStateHeading('Nessun click nel periodo')
            ->paginated(false)
            ->striped();
    }
}
