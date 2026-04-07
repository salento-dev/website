<?php

namespace App\Filament\Resources\StoryCategories\Pages;

use App\Filament\Resources\StoryCategories\StoryCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStoryCategories extends ListRecords
{
    protected static string $resource = StoryCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
