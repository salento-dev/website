<?php

namespace App\Filament\Resources\StoryCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StoryCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('sort_order')
                    ->label('Ordinamento')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('Attiva')
                    ->default(true),
            ])
            ->columns(2);
    }
}
