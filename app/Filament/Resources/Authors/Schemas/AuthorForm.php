<?php

namespace App\Filament\Resources\Authors\Schemas;

use App\Models\Author;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
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
                            ->placeholder('es. Full Stack Dev')
                            ->helperText(function (Get $get): ?string {
                                $username = $get('github_username');
                                if (blank($username)) {
                                    return null;
                                }
                                $company = Author::fetchGithubProfile($username)['company'] ?? null;

                                return filled($company) ? 'GitHub: '.ltrim((string) $company, '@') : null;
                            }),
                        Textarea::make('bio')
                            ->label('Biografia')
                            ->rows(3)
                            ->maxLength(1000)
                            ->columnSpanFull()
                            ->helperText(function (Get $get): ?string {
                                $username = $get('github_username');
                                if (blank($username)) {
                                    return null;
                                }
                                $bio = Author::fetchGithubProfile($username)['bio'] ?? null;

                                return filled($bio) ? 'GitHub: '.(string) $bio : null;
                            }),
                    ]),

                Section::make('Avatar')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('avatar_path')
                            ->label('Immagine personalizzata')
                            ->helperText('Se non caricata, verrà usato l\'avatar GitHub.')
                            ->image()
                            ->disk('public')
                            ->directory('authors'),
                        Image::make(
                            url: fn ($record) => $record?->avatar_url ?? 'https://ui-avatars.com/api/?name=&background=0a0a0a&color=06b6d4',
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
                            ->prefix('https://')
                            ->helperText(function (Get $get): ?string {
                                $username = $get('github_username');
                                if (blank($username)) {
                                    return null;
                                }
                                $twitter = Author::fetchGithubProfile($username)['twitter_username'] ?? null;

                                return filled($twitter) ? 'GitHub: @'.(string) $twitter : null;
                            }),
                        TextInput::make('website_url')
                            ->label('Sito web')
                            ->url()
                            ->maxLength(2048)
                            ->prefix('https://')
                            ->helperText(function (Get $get): ?string {
                                $username = $get('github_username');
                                if (blank($username)) {
                                    return null;
                                }
                                $blog = Author::fetchGithubProfile($username)['blog'] ?? null;

                                return filled($blog) ? 'GitHub: '.(string) $blog : null;
                            }),
                    ]),
            ]);
    }
}
