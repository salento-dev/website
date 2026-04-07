<?php

namespace App\Filament\Resources\StoryCategories;

use App\Filament\Resources\StoryCategories\Pages\CreateStoryCategory;
use App\Filament\Resources\StoryCategories\Pages\EditStoryCategory;
use App\Filament\Resources\StoryCategories\Pages\ListStoryCategories;
use App\Filament\Resources\StoryCategories\Schemas\StoryCategoryForm;
use App\Filament\Resources\StoryCategories\Tables\StoryCategoriesTable;
use App\Models\StoryCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class StoryCategoryResource extends Resource
{
    protected static ?string $model = StoryCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $navigationLabel = 'Categorie';

    protected static ?string $modelLabel = 'Categoria';

    protected static ?string $pluralModelLabel = 'Categorie Storie';

    protected static string|UnitEnum|null $navigationGroup = 'Contenuti';

    public static function form(Schema $schema): Schema
    {
        return StoryCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StoryCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStoryCategories::route('/'),
            'create' => CreateStoryCategory::route('/create'),
            'edit' => EditStoryCategory::route('/{record}/edit'),
        ];
    }
}
