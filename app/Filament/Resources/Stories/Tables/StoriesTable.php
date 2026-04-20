<?php

namespace App\Filament\Resources\Stories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class StoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Copertina')
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')
                    ->label('Titolo')
                    ->searchable()
                    ->sortable(),
                ImageColumn::make('authors.avatar_url')
                    ->imageHeight(36)
                    ->circular()
                    ->stacked(),
                TextColumn::make('category.name')
                    ->label('Categoria')
                    ->badge()
                    ->sortable(),
                TextColumn::make('read_time_minutes')
                    ->label('Lettura (min)')
                    ->suffix(' min')
                    ->toggleable(),
                IconColumn::make('is_published')
                    ->label('Pubblicato')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->label('Pubblicato il')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label('Aggiornato')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Pubblicato'),
                SelectFilter::make('story_category_id')
                    ->label('Categoria')
                    ->relationship('category', 'name'),
            ])
            ->defaultSort('published_at', 'desc')
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
