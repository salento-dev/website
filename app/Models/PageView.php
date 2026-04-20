<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PageView extends Model
{
    public const UPDATED_AT = null;

    /** @var list<string> */
    protected $fillable = [
        'path',
        'url',
        'referrer',
        'user_agent',
        'viewable_type',
        'viewable_id',
    ];

    public function viewable(): MorphTo
    {
        return $this->morphTo();
    }
}
