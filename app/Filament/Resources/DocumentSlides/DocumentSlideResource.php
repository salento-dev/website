<?php

namespace App\Filament\Resources\DocumentSlides;

use App\Filament\Resources\DocumentSlides\Pages\CreateDocumentSlide;
use App\Filament\Resources\DocumentSlides\Pages\EditDocumentSlide;
use App\Filament\Resources\DocumentSlides\Pages\ListDocumentSlides;
use App\Filament\Resources\DocumentSlides\Schemas\DocumentSlideForm;
use App\Filament\Resources\DocumentSlides\Tables\DocumentSlidesTable;
use App\Models\DocumentSlide;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DocumentSlideResource extends Resource
{
    protected static ?string $model = DocumentSlide::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Documenti & Slide';

    protected static ?string $modelLabel = 'Documento / Slide';

    protected static ?string $pluralModelLabel = 'Documenti / Slide';

    protected static string|UnitEnum|null $navigationGroup = 'Contenuti';

    public static function form(Schema $schema): Schema
    {
        return DocumentSlideForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentSlidesTable::configure($table);
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
            'index' => ListDocumentSlides::route('/'),
            'create' => CreateDocumentSlide::route('/create'),
            'edit' => EditDocumentSlide::route('/{record}/edit'),
        ];
    }
}
