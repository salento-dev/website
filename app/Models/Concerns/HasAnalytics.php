<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\AnalyticsDailyStat;
use App\Models\AnalyticsEvent;
use App\Models\PageView;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @mixin Model
 */
trait HasAnalytics
{
    public function pageViews(): MorphMany
    {
        return $this->morphMany(PageView::class, 'viewable');
    }

    public function analyticsEvents(): MorphMany
    {
        return $this->morphMany(AnalyticsEvent::class, 'viewable');
    }

    /**
     * Page views already rolled up into daily aggregates by the retention job.
     */
    public function archivedPageViews(): MorphMany
    {
        return $this->morphMany(AnalyticsDailyStat::class, 'viewable')
            ->where('metric', AnalyticsDailyStat::METRIC_VIEW);
    }

    /**
     * Eager load the raw and archived view counters used by {@see totalPageViews()}.
     *
     * @param  Builder<static>  $query
     */
    public function scopeWithTotalPageViews(Builder $query): void
    {
        $query->withCount('pageViews')->withSum('archivedPageViews', 'count');
    }

    public function loadTotalPageViews(): static
    {
        return $this->loadCount('pageViews')->loadSum('archivedPageViews', 'count');
    }

    public function totalPageViews(): int
    {
        return (int) ($this->page_views_count ?? 0) + (int) ($this->archived_page_views_sum_count ?? 0);
    }
}
