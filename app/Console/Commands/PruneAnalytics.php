<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\AnalyticsDailyStat;
use App\Models\AnalyticsEvent;
use App\Models\NotFoundHit;
use App\Models\PageView;
use App\Support\Analytics\VisitorHash;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PruneAnalytics extends Command
{
    /**
     * @var string
     */
    protected $signature = 'analytics:prune';

    /**
     * @var string
     */
    protected $description = 'Roll up raw analytics older than the retention window into daily stats and delete them';

    public function handle(): int
    {
        $cutoff = now()->subMonths((int) config('analytics.retention_months'))->startOfDay();

        $views = DB::transaction(fn (): int => $this->rollUp(
            PageView::query()->where('created_at', '<', $cutoff),
            "'".AnalyticsDailyStat::METRIC_VIEW."'",
        ));

        $events = DB::transaction(fn (): int => $this->rollUp(
            AnalyticsEvent::query()->where('created_at', '<', $cutoff),
            'name',
        ));

        $notFound = NotFoundHit::query()->where('last_seen_at', '<', $cutoff)->delete();

        VisitorHash::forgetSaltsBefore(now()->toDateString());

        $this->components->info("Archiviate {$views} visite e {$events} click, rimossi {$notFound} percorsi 404 anteriori al {$cutoff->toDateString()}.");

        return self::SUCCESS;
    }

    /**
     * Aggregate the given raw rows per day, metric and path, then delete them.
     *
     * @param  Builder<PageView>|Builder<AnalyticsEvent>  $query
     * @param  string  $metricSql  SQL expression selecting the metric name (a column or a quoted literal)
     */
    private function rollUp(Builder $query, string $metricSql): int
    {
        $groups = (clone $query)
            ->select([
                DB::raw('DATE(created_at) as day'),
                DB::raw("{$metricSql} as metric"),
                'path',
                'viewable_type',
                'viewable_id',
                DB::raw('COUNT(*) as total'),
                DB::raw('COUNT(DISTINCT visitor_hash) as unique_visitors'),
            ])
            ->groupBy('day', 'metric', 'path', 'viewable_type', 'viewable_id')
            ->toBase()
            ->get();

        foreach ($groups as $group) {
            $stat = AnalyticsDailyStat::query()->firstOrNew([
                'date' => Carbon::parse($group->day)->toDateString(),
                'metric' => $group->metric,
                'path' => $group->path,
            ]);

            $stat->viewable_type ??= $group->viewable_type;
            $stat->viewable_id ??= $group->viewable_id;
            $stat->count = ($stat->count ?? 0) + (int) $group->total;
            $stat->visitors = ($stat->visitors ?? 0) + (int) $group->unique_visitors;
            $stat->save();
        }

        return $query->delete();
    }
}
