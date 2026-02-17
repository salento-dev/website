<?php

namespace App\Filament\Resources\Resources\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->label('Titolo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('starts_at')
                    ->label('Inizio')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('location')
                    ->label('Luogo')
                    ->searchable(),
                IconColumn::make('is_published')
                    ->label('Pubblicato')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect(),
            ])
            ->recordActions([
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ])
            ->defaultSort('starts_at', 'desc');
    }
}
