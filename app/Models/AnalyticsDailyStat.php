<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Daily roll-up of raw page views and click events older than the retention window.
 */
class AnalyticsDailyStat extends Model
{
    public const METRIC_VIEW = 'view';

    public $timestamps = false;

    /** @var list<string> */
    protected $fillable = [
        'date',
        'metric',
        'path',
        'viewable_type',
        'viewable_id',
        'count',
        'visitors',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'count' => 'integer',
            'visitors' => 'integer',
        ];
    }

    public function viewable(): MorphTo
    {
        return $this->morphTo();
    }
}
