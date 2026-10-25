<?php

declare(strict_types=1);

namespace App\Filament\Resources\PageViews\Tables;

use App\Support\Analytics\UserAgent;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PageViewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('path')
                    ->label('Pagina')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('referrer_host')
                    ->label('Provenienza')
                    ->searchable()
                    ->placeholder('Diretto')
                    ->toggleable(),
                TextColumn::make('utm_campaign')
                    ->label('Campagna')
                    ->searchable()
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('device')
                    ->label('Dispositivo')
                    ->formatStateUsing(fn (?string $state): string => UserAgent::DEVICE_LABELS[$state] ?? '—')
                    ->toggleable(),
                TextColumn::make('referrer')
                    ->label('Referrer completo')
                    ->placeholder('—')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('user_agent')
                    ->label('User Agent')
                    ->placeholder('—')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->timezone('Europe/Rome')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('today')
                    ->label('Oggi')
                    ->query(fn (Builder $query) => $query->whereDate('created_at', today())),
                Filter::make('this_week')
                    ->label('Questa settimana')
                    ->query(fn (Builder $query) => $query->where('created_at', '>=', now()->startOfWeek())),
                Filter::make('this_month')
                    ->label('Questo mese')
                    ->query(fn (Builder $query) => $query->where('created_at', '>=', now()->startOfMonth())),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([25, 50, 100]);
    }
}
