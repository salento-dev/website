<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\NotFoundHit;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class NotFoundHitsWidget extends TableWidget
{
    protected static ?int $sort = 10;

    protected static ?string $heading = 'Pagine non trovate (404)';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->description('Link rotti o vecchi indirizzi ancora condivisi. Segna come risolto dopo aver corretto il link.')
            ->query(fn (): Builder => NotFoundHit::query())
            ->defaultSort('hits', 'desc')
            ->columns([
                TextColumn::make('path')
                    ->label('Percorso')
                    ->searchable(),
                TextColumn::make('hits')
                    ->label('Richieste')
                    ->numeric()
                    ->sortable()
                    ->alignEnd(),
                TextColumn::make('last_referrer')
                    ->label('Ultima provenienza')
                    ->placeholder('—')
                    ->limit(60),
                TextColumn::make('last_seen_at')
                    ->label('Ultima volta')
                    ->dateTime('d/m/Y H:i')
                    ->timezone('Europe/Rome')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('resolve')
                    ->label('Risolto')
                    ->icon(Heroicon::OutlinedCheck)
                    ->requiresConfirmation()
                    ->action(fn (NotFoundHit $record) => $record->delete()),
            ])
            ->emptyStateHeading('Nessuna pagina non trovata')
            ->paginated([10, 25, 50]);
    }
}
