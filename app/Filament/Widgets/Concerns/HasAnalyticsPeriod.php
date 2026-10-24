<?php

declare(strict_types=1);

namespace App\Filament\Widgets\Concerns;

use App\Support\Analytics\ReportingPeriod;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait HasAnalyticsPeriod
{
    use InteractsWithPageFilters;

    protected function period(): ReportingPeriod
    {
        return ReportingPeriod::fromFilters($this->pageFilters);
    }

    /**
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    protected function inPeriod(Builder $query, ?ReportingPeriod $period = null, string $column = 'created_at'): Builder
    {
        return $query->whereBetween($query->qualifyColumn($column), ($period ?? $this->period())->bounds());
    }
}
