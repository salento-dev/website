<?php

namespace App\Filament\Resources\StoryCategories\Pages;

use App\Filament\Resources\StoryCategories\StoryCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStoryCategory extends EditRecord
{
    protected static string $resource = StoryCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
