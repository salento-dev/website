<?php

namespace App\Filament\Resources\DocumentSlides\Pages;

use App\Filament\Resources\DocumentSlides\DocumentSlideResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDocumentSlide extends EditRecord
{
    protected static string $resource = DocumentSlideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
