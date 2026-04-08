<?php

namespace App\Filament\Resources\Stories\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StoryForm
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
                Select::make('story_category_id')
                    ->label('Categoria')
                    ->relationship('category', 'name', fn ($query) => $query->where('is_active', true)->orderBy('sort_order')->orderBy('name'))
                    ->preload()
                    ->searchable(),
                Select::make('authors')
                    ->label('Autori')
                    ->relationship('authors', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('role')
                            ->label('Ruolo')
                            ->maxLength(255),
                        TextInput::make('github_username')
                            ->label('GitHub Username')
                            ->maxLength(255),
                    ])
                    ->columnSpanFull(),
                MarkdownEditor::make('excerpt')
                    ->label('Estratto')
                    ->required()
                    ->columnSpanFull(),
                MarkdownEditor::make('body')
                    ->label('Corpo')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('image_path')
                    ->label('Immagine di copertina')
                    ->image()
                    ->disk('public')
                    ->directory('stories')
                    ->visibility('public')
                    ->columnSpanFull(),
                Toggle::make('is_published')
                    ->label('Pubblicato')
                    ->default(false)
                    ->live(),
                DateTimePicker::make('published_at')
                    ->label('Data di pubblicazione')
                    ->timezone('Europe/Rome')
                    ->seconds(false)
                    ->visible(fn ($get): bool => (bool) $get('is_published')),
            ])
            ->columns(2);
    }
}
