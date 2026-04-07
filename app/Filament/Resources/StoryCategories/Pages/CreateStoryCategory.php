<?php

namespace App\Filament\Resources\StoryCategories\Pages;

use App\Filament\Resources\StoryCategories\StoryCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStoryCategory extends CreateRecord
{
    protected static string $resource = StoryCategoryResource::class;
}
