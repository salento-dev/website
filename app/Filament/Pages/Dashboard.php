<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Widgets\ClickEventsWidget;
use App\Filament\Widgets\DevicesChartWidget;
use App\Filament\Widgets\NotFoundHitsWidget;
use App\Filament\Widgets\PageViewsChartWidget;
use App\Filament\Widgets\PageViewStatsWidget;
use App\Filament\Widgets\TopCampaignsWidget;
use App\Filament\Widgets\TopEventsWidget;
use App\Filament\Widgets\TopPagesWidget;
use App\Filament\Widgets\TopSourcesWidget;
use App\Filament\Widgets\TopStoriesWidget;
use App\Models\NotFoundHit;
use App\Support\Analytics\ReportingPeriod;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Widget;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function getSubheading(): string
    {
        $period = ReportingPeriod::fromFilters($this->filters);

        return sprintf(
            'Dal %s al %s (%d giorni), confrontati con i %d giorni precedenti.',
            $period->start->format('d/m/Y'),
            $period->end->format('d/m/Y'),
            $period->days(),
            $period->days(),
        );
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('period')
                    ->label('Periodo')
                    ->hiddenLabel()
                    ->prefixIcon(Heroicon::OutlinedCalendarDays)
                    ->options(ReportingPeriod::OPTIONS)
                    ->default(ReportingPeriod::DEFAULT)
                    ->selectablePlaceholder(false)
                    ->live(),
                DatePicker::make('startDate')
                    ->label('Data di inizio')
                    ->hiddenLabel()
                    ->prefix('Dal')
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->maxDate(now())
                    ->visible(fn (Get $get): bool => $get('period') === ReportingPeriod::CUSTOM),
                DatePicker::make('endDate')
                    ->label('Data di fine')
                    ->hiddenLabel()
                    ->prefix('Al')
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->maxDate(now())
                    ->visible(fn (Get $get): bool => $get('period') === ReportingPeriod::CUSTOM),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFiltersFormContentComponent(),
                $this->widgetGrid([PageViewStatsWidget::class]),
                Tabs::make('Analitiche')
                    ->persistTabInQueryString('sezione')
                    ->scrollable(false)
                    ->contained(false)
                    ->tabs([
                        Tab::make('Panoramica')
                            ->id('panoramica')
                            ->schema([
                                $this->widgetGrid([
                                    PageViewsChartWidget::class,
                                    ClickEventsWidget::class,
                                    TopPagesWidget::class,
                                ]),
                            ]),
                        Tab::make('Provenienza')
                            ->id('provenienza')
                            ->schema([
                                $this->widgetGrid([
                                    TopSourcesWidget::class,
                                    DevicesChartWidget::class,
                                    TopCampaignsWidget::class,
                                ]),
                            ]),
                        Tab::make('Contenuti')
                            ->id('contenuti')
                            ->schema([
                                $this->widgetGrid([
                                    TopEventsWidget::class,
                                    TopStoriesWidget::class,
                                ]),
                            ]),
                        Tab::make('Errori 404')
                            ->id('404')
                            ->badge(fn (): ?int => NotFoundHit::query()->count() ?: null)
                            ->badgeColor('warning')
                            ->schema([
                                $this->widgetGrid([NotFoundHitsWidget::class]),
                            ]),
                    ]),
            ]);
    }

    /**
     * @param  list<class-string<Widget>>  $widgets
     */
    private function widgetGrid(array $widgets): Grid
    {
        return Grid::make(['default' => 1, 'lg' => 2])
            ->schema(fn (): array => $this->getWidgetsSchemaComponents($widgets));
    }
}
