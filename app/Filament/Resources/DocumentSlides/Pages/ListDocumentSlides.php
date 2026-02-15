<?php

namespace App\Filament\Resources\DocumentSlides\Pages;

use App\Filament\Resources\DocumentSlides\DocumentSlideResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDocumentSlides extends ListRecords
{
    protected static string $resource = DocumentSlideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
