<?php

declare(strict_types=1);

namespace App\Filament\Resources\PageViews;

use App\Filament\Resources\PageViews\Pages\ListPageViews;
use App\Filament\Resources\PageViews\Tables\PageViewsTable;
use App\Models\PageView;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PageViewResource extends Resource
{
    protected static ?string $model = PageView::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'Visite';

    protected static ?string $modelLabel = 'Visita';

    protected static ?string $pluralModelLabel = 'Visite';

    protected static string|UnitEnum|null $navigationGroup = 'Analitiche';

    protected static ?int $navigationSort = 99;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return PageViewsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPageViews::route('/'),
        ];
    }
}
