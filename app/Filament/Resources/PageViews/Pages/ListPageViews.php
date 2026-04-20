<?php

declare(strict_types=1);

namespace App\Filament\Resources\PageViews\Pages;

use App\Filament\Resources\PageViews\PageViewResource;
use Filament\Resources\Pages\ListRecords;

class ListPageViews extends ListRecords
{
    protected static string $resource = PageViewResource::class;
}
