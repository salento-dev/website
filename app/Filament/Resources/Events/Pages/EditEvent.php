<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('covers')
                ->label('Copertine')
                ->icon(Heroicon::OutlinedPhoto)
                ->color('gray')
                ->url(fn (): string => EventCovers::getUrl(['record' => $this->getRecord()]))
                ->visible(fn (): bool => (bool) $this->getRecord()->is_community_event),
            DeleteAction::make(),
        ];
    }
}
