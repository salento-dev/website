<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Titolo')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('location')
                    ->label('Luogo')
                    ->required()
                    ->maxLength(255),
                TextInput::make('address')
                    ->label('Indirizzo')
                    ->maxLength(255),
                DateTimePicker::make('starts_at')
                    ->label('Data inizio')
                    ->required()
                    ->seconds(false),
                DateTimePicker::make('ends_at')
                    ->label('Data fine')
                    ->seconds(false)
                    ->afterOrEqual('starts_at'),
                Textarea::make('description')
                    ->label('Descrizione')
                    ->required()
                    ->rows(6)
                    ->columnSpanFull(),
                FileUpload::make('image_path')
                    ->label('Immagine')
                    ->image()
                    ->required()
                    ->disk('public')
                    ->directory('events')
                    ->visibility('public')
                    ->columnSpanFull(),
                Toggle::make('is_published')
                    ->label('Pubblicato')
                    ->default(true),
            ])
            ->columns(2);
    }
}
