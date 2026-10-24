<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\AnalyticsEventFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AnalyticsEvent extends Model
{
    /** @use HasFactory<AnalyticsEventFactory> */
    use HasFactory;

    public const UPDATED_AT = null;

    public const WHATSAPP = 'whatsapp';

    public const INSTAGRAM = 'instagram';

    public const LINKEDIN = 'linkedin';

    public const EMAIL = 'email';

    public const RESOURCE = 'resource';

    public const OUTBOUND = 'outbound';

    /** @var array<string, string> */
    public const LABELS = [
        self::WHATSAPP => 'Gruppo WhatsApp',
        self::INSTAGRAM => 'Instagram',
        self::LINKEDIN => 'LinkedIn',
        self::EMAIL => 'Email',
        self::RESOURCE => 'Risorse',
        self::OUTBOUND => 'Link esterni',
    ];

    /** @var list<string> */
    protected $fillable = [
        'name',
        'path',
        'viewable_type',
        'viewable_id',
        'target_url',
        'target_host',
        'label',
        'visitor_hash',
        'created_at',
    ];

    public function viewable(): MorphTo
    {
        return $this->morphTo();
    }

    public static function labelFor(string $name): string
    {
        return self::LABELS[$name] ?? $name;
    }
}
