<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\PageView;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TopReferrersWidget extends TableWidget
{
    protected static ?int $sort = 4;

    protected static ?string $heading = 'Principali sorgenti di traffico';

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = '60s';

    public function getTableRecordKey(Model|array $record): string
    {
        $referrer = is_array($record) ? $record['referrer'] : $record->referrer;

        return md5((string) $referrer);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn (): Builder => PageView::query()
                    ->selectRaw('referrer, count(*) as views')
                    ->whereNotNull('referrer')
                    ->where('referrer', '!=', '')
                    ->where('referrer', 'not like', '%'.parse_url(config('app.url'), PHP_URL_HOST).'%')
                    ->groupBy('referrer')
                    ->orderByDesc('views')
                    ->limit(10)
            )
            ->defaultKeySort(false)
            ->columns([
                TextColumn::make('rank')
                    ->label('#')
                    ->rowIndex()
                    ->width('48px'),
                TextColumn::make('referrer')
                    ->label('Sorgente'),
                TextColumn::make('views')
                    ->label('Visite')
                    ->numeric()
                    ->alignEnd(),
            ])
            ->paginated(false)
            ->striped();
    }
}
