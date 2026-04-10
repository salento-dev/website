<?php

namespace App\Filament\Resources\Authors\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuthorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar_url')
                    ->label('Avatar')
                    ->circular(),
                    // ->url(fn ($record): string => $record->avatarUrl()),
                    // ->defaultImageUrl(fn ($record): string => $record->avatarUrl() ?? 'https://ui-avatars.com/api/?name='.urlencode((string) $record->name).'&background=0a0a0a&color=06b6d4'),
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role')
                    ->label('Ruolo')
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('github_username')
                    ->label('GitHub')
                    ->prefix('@')
                    ->placeholder('—')
                    ->url(fn ($record): ?string => $record->github_url)
                    ->openUrlInNewTab(),
                TextColumn::make('stories_count')
                    ->label('Storie')
                    ->counts('stories')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Aggiornato')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->defaultSort('name')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
