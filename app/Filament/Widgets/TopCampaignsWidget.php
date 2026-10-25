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

class TopCampaignsWidget extends TableWidget
{
    use HasAnalyticsPeriod;

    protected static ?int $sort = 6;

    protected static ?string $heading = 'Campagne (UTM)';

    protected int|string|array $columnSpan = 'full';

    public function getTableRecordKey(Model|array $record): string
    {
        $record = is_array($record) ? $record : $record->getAttributes();

        return md5($record['utm_campaign'].'|'.$record['utm_source'].'|'.$record['utm_medium']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->description('Link con utm_campaign, per esempio post social o QR code ai meetup.')
            ->query(
                fn (): Builder => $this->inPeriod(PageView::query())
                    ->whereNotNull('utm_campaign')
                    ->selectRaw('utm_campaign, utm_source, utm_medium, COUNT(*) as views, COUNT(DISTINCT visitor_hash) as visitors')
                    ->groupBy('utm_campaign', 'utm_source', 'utm_medium')
                    ->orderByDesc('views')
                    ->limit(10)
            )
            ->defaultKeySort(false)
            ->columns([
                TextColumn::make('utm_campaign')
                    ->label('Campagna'),
                TextColumn::make('utm_source')
                    ->label('Sorgente')
                    ->placeholder('—'),
                TextColumn::make('utm_medium')
                    ->label('Mezzo')
                    ->placeholder('—'),
                TextColumn::make('views')
                    ->label('Visite')
                    ->numeric()
                    ->alignEnd(),
                TextColumn::make('visitors')
                    ->label('Visitatori')
                    ->numeric()
                    ->alignEnd(),
            ])
            ->emptyStateHeading('Nessuna campagna nel periodo')
            ->emptyStateDescription('Aggiungi ?utm_source=instagram&utm_campaign=meetup-ottobre ai link condivisi.')
            ->paginated(false)
            ->striped();
    }
}
