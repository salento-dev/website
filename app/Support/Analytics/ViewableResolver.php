<?php

declare(strict_types=1);

namespace App\Support\Analytics;

use App\Models\Event;
use App\Models\Story;
use Illuminate\Database\Eloquent\Model;

class ViewableResolver
{
    /** @var array<string, class-string<Model>> */
    private const PATH_PREFIXES = [
        'events' => Event::class,
        'stories' => Story::class,
    ];

    /**
     * Resolve the event or story a public path points to.
     */
    public static function fromPath(string $path): ?Model
    {
        if (preg_match('#^/(events|stories)/([^/?\#]+)/?$#', $path, $matches) !== 1) {
            return null;
        }

        $class = self::PATH_PREFIXES[$matches[1]];

        return $class::query()->where('slug', $matches[2])->first();
    }
}
