<?php

namespace App\Filament\Resources\Resources\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ResourceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Titolo')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Descrizione')
                    ->required()
                    ->rows(6)
                    ->columnSpanFull(),
                Toggle::make('is_published')
                    ->label('Pubblicato')
                    ->inline(false)
                    ->default(true),
                Select::make('source_type')
                    ->label('Tipo contenuto')
                    ->options([
                        'link' => 'Link',
                        'file' => 'File',
                    ])
                    ->default('link')
                    ->required()
                    ->native(false)
                    ->live()
                    ->columnSpanFull(),

                TextInput::make('external_url')
                    ->label('Link')
                    ->url()
                    ->maxLength(2048)
                    ->visible(fn($get): bool => $get('source_type') === 'link')
                    ->required(fn($get): bool => $get('source_type') === 'link')
                    ->columnSpanFull(),
                FileUpload::make('file_path')
                    ->label('File')
                    ->disk('public')
                    ->directory('resources/files')
                    ->visibility('public')
                    ->visible(fn($get): bool => $get('source_type') === 'file')
                    ->required(fn($get): bool => $get('source_type') === 'file')
                    ->columnSpanFull(),
                Select::make('authors')
                    ->label('Autori')
                    ->relationship('authors', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->columnSpanFull(),
            ])
            ->columns(1);
    }
}
