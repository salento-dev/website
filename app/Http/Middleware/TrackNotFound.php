<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\NotFoundHit;
use App\Support\Analytics\UserAgent;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Counts 404 responses per path so broken links can be spotted and fixed.
 */
class TrackNotFound
{
    private const IGNORED_PREFIXES = ['admin', 'livewire', 'build', 'storage', 'wp-', '.', 'cgi-bin', 'vendor'];

    private const IGNORED_EXTENSIONS = '/\.(php\d?|aspx?|jsp|cgi|env|git|sql|bak|old|zip|gz|tar|rar|xml|txt|ico|png|jpe?g|gif|svg|webp|css|js|map|json|ya?ml|ini|log|conf)$/i';

    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        if (! $this->shouldTrack($request, $response)) {
            return;
        }

        $path = Str::limit('/'.ltrim($request->path(), '/'), 255, '');
        $referrer = $request->headers->get('referer');
        $referrer = $referrer !== null ? Str::limit($referrer, 255, '') : null;
        $now = now();

        $updated = NotFoundHit::query()->where('path', $path)->increment('hits', 1, array_filter([
            'last_referrer' => $referrer,
            'last_seen_at' => $now,
        ], fn (mixed $value): bool => $value !== null));

        if ($updated === 0) {
            NotFoundHit::query()->create([
                'path' => $path,
                'hits' => 1,
                'last_referrer' => $referrer,
                'first_seen_at' => $now,
                'last_seen_at' => $now,
            ]);
        }
    }

    private function shouldTrack(Request $request, Response $response): bool
    {
        if ($response->getStatusCode() !== 404 || ! $request->isMethod('GET')) {
            return false;
        }

        $path = ltrim($request->path(), '/');

        foreach (self::IGNORED_PREFIXES as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return false;
            }
        }

        if (preg_match(self::IGNORED_EXTENSIONS, $path) === 1) {
            return false;
        }

        return ! UserAgent::isBot($request->userAgent());
    }
}
