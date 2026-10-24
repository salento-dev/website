<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PageViewFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PageView extends Model
{
    /** @use HasFactory<PageViewFactory> */
    use HasFactory;

    public const UPDATED_AT = null;

    /** @var list<string> */
    protected $fillable = [
        'path',
        'url',
        'visitor_hash',
        'referrer',
        'referrer_host',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'user_agent',
        'device',
        'viewable_type',
        'viewable_id',
        'created_at',
    ];

    public function viewable(): MorphTo
    {
        return $this->morphTo();
    }
}
