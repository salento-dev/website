<?php

namespace App\Filament\Resources\Documents\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DocumentForm
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
                Select::make('source_type')
                    ->label('Tipo contenuto')
                    ->options([
                        'link' => 'Link',
                        'file' => 'File',
                    ])
                    ->default('link')
                    ->required()
                    ->native(false)
                    ->live(),
                Toggle::make('is_published')
                    ->label('Pubblicato')
                    ->default(true),
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
                    ->directory('documents/images')
                    ->visibility('public')
                    ->columnSpanFull(),
                TextInput::make('external_url')
                    ->label('Link')
                    ->url()
                    ->maxLength(2048)
                    ->visible(fn ($get): bool => $get('source_type') === 'link')
                    ->required(fn ($get): bool => $get('source_type') === 'link')
                    ->columnSpanFull(),
                FileUpload::make('file_path')
                    ->label('File')
                    ->disk('public')
                    ->directory('documents/files')
                    ->visibility('public')
                    ->visible(fn ($get): bool => $get('source_type') === 'file')
                    ->required(fn ($get): bool => $get('source_type') === 'file')
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
