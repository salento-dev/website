<?php

namespace App\Filament\Resources\Authors\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;

class AuthorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identità')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('role')
                            ->label('Ruolo')
                            ->maxLength(255)
                            ->placeholder('es. Full Stack Dev'),
                        Textarea::make('bio')
                            ->label('Biografia')
                            ->rows(3)
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ]),

                Section::make('Avatar')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('avatar_path')
                            ->label('Immagine personalizzata')
                            ->helperText('Se non caricata, verrà usato l\'avatar GitHub.')
                            ->image()
                            ->disk('public')
                            ->directory('authors')
                            ->visibility('public'),
                        Image::make(
                            url: function ($get) {
                                $username = $get('github_username');
                                if (blank($username)) {
                                    return null;
                                }
                                return 'https://github.com/' . htmlspecialchars((string) $username, ENT_QUOTES) . '.png?size=200';
                            },
                            alt: 'GitHub avatar'
                        )
                        ->live(),
                    ]),

                Section::make('Profili Social')
                    ->columns(2)
                    ->schema([
                        TextInput::make('github_username')
                            ->label('GitHub Username')
                            ->prefix('github.com/')
                            ->maxLength(255)
                            ->live(debounce: 500),
                        TextInput::make('linkedin_url')
                            ->label('LinkedIn URL')
                            ->url()
                            ->maxLength(2048)
                            ->prefix('https://'),
                        TextInput::make('twitter_url')
                            ->label('X / Twitter URL')
                            ->url()
                            ->maxLength(2048)
                            ->prefix('https://'),
                        TextInput::make('website_url')
                            ->label('Sito web')
                            ->url()
                            ->maxLength(2048)
                            ->prefix('https://'),
                    ]),
            ]);
    }
}
