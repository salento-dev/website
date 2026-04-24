<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
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
                    ->maxLength(255),
                TextInput::make('address')
                    ->label('Indirizzo')
                    ->maxLength(255),
                DateTimePicker::make('starts_at')
                    ->label('Data inizio')
                    ->timezone('Europe/Rome')
                    ->required()
                    ->seconds(false),
                DateTimePicker::make('ends_at')
                    ->label('Data fine')
                    ->timezone('Europe/Rome')
                    ->seconds(false)
                    ->afterOrEqual('starts_at'),
                Textarea::make('excerpt')
                    ->label('Estratto')
                    ->helperText('Breve descrizione mostrata nelle anteprime (card, slideshow). Se vuoto, viene usata la descrizione.')
                    ->rows(3)
                    ->maxLength(500)
                    ->columnSpanFull(),
                MarkdownEditor::make('body')
                    ->label('Corpo')
                    ->columnSpanFull(),
                FileUpload::make('image_path')
                    ->label('Immagine')
                    ->image()
                    ->disk('public')
                    ->directory('events')
                    ->visibility('public')
                    ->columnSpanFull(),
                Toggle::make('is_published')
                    ->label('Pubblicato')
                    ->default(true),
                Toggle::make('work_in_progress')
                    ->label('Work in Progress')
                    ->default(false)
                    ->helperText('Gli eventi work in progress sono visibili nella lista ma non accessibili nel dettaglio'),
                Toggle::make('is_community_event')
                    ->label('Evento della community')
                    ->default(true)
                    ->helperText('Attiva se l\'evento è organizzato direttamente da salento.dev'),
                Select::make('categories')
                    ->label('Categorie')
                    ->relationship('categories', 'name', fn ($query) => $query->where('is_active', true)->orderBy('sort_order')->orderBy('name'))
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->columnSpanFull(),
                Select::make('partners')
                    ->label('Partner')
                    ->relationship('partners', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->columnSpanFull(),
                Repeater::make('media')
                    ->label('Galleria media')
                    ->relationship('media')
                    ->orderColumn('sort_order')
                    ->collapsed()
                    ->cloneable()
                    ->itemLabel(fn (array $state): ?string => $state['caption'] ?? null)
                    ->default([])
                    ->schema([
                        Select::make('media_type')
                            ->label('Tipo media')
                            ->options([
                                'image' => 'Foto',
                                'video' => 'Video',
                            ])
                            ->default('image')
                            ->required()
                            ->native(false),
                        Select::make('source_type')
                            ->label('Sorgente')
                            ->options([
                                'file' => 'File',
                                'url' => 'URL video',
                            ])
                            ->default('file')
                            ->required()
                            ->native(false)
                            ->live(),
                        FileUpload::make('file_path')
                            ->label('File media')
                            ->disk('public')
                            ->directory('events/media')
                            ->visibility('public')
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                                'image/gif',
                                'video/mp4',
                                'video/webm',
                            ])
                            ->visible(fn ($get): bool => $get('source_type') === 'file')
                            ->required(fn ($get): bool => $get('source_type') === 'file')
                            ->columnSpanFull(),
                        TextInput::make('external_url')
                            ->label('URL video')
                            ->url()
                            ->maxLength(2048)
                            ->visible(fn ($get): bool => $get('source_type') === 'url')
                            ->required(fn ($get): bool => $get('source_type') === 'url')
                            ->columnSpanFull(),
                        TextInput::make('caption')
                            ->label('Didascalia')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
