<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Event;
use App\Models\PageView;
use App\Models\Story;
use App\Support\Analytics\ReferrerSource;
use App\Support\Analytics\UserAgent;
use App\Support\Analytics\VisitorHash;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    private const REQUEST_KEY = '_track_pv';

    /** @var array<string, class-string<Model>> */
    private const VIEWABLE_PARAMS = [
        'event' => Event::class,
        'story' => Story::class,
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->shouldTrack($request, $response)) {
            return $response;
        }

        $request->attributes->set(self::REQUEST_KEY, [
            'path' => $request->path() === '/' ? '/' : '/'.$request->path(),
            'viewable' => $this->resolveViewable($request),
        ]);

        return $response;
    }

    public function terminate(Request $request, Response $_response): void
    {
        $data = $request->attributes->get(self::REQUEST_KEY);

        if ($data === null) {
            return;
        }

        $visitorHash = VisitorHash::fromRequest($request);

        if ($this->recentlyViewed($visitorHash, $data['path'])) {
            return;
        }

        /** @var Model|null $viewable */
        $viewable = $data['viewable'];
        $referrer = $request->headers->get('referer');

        PageView::create([
            'path' => Str::limit($data['path'], 255, ''),
            'url' => $request->fullUrl(),
            'visitor_hash' => $visitorHash,
            'referrer' => $referrer !== null ? Str::limit($referrer, 255, '') : null,
            'referrer_host' => ReferrerSource::fromReferrer($referrer, $request->getHost()),
            'utm_source' => $this->utm($request, 'utm_source'),
            'utm_medium' => $this->utm($request, 'utm_medium'),
            'utm_campaign' => $this->utm($request, 'utm_campaign'),
            'user_agent' => Str::limit((string) $request->userAgent(), 255, ''),
            'device' => UserAgent::device($request->userAgent()),
            'viewable_type' => $viewable?->getMorphClass(),
            'viewable_id' => $viewable?->getKey(),
        ]);
    }

    private function recentlyViewed(string $visitorHash, string $path): bool
    {
        return PageView::query()
            ->where('visitor_hash', $visitorHash)
            ->where('path', $path)
            ->where('created_at', '>=', now()->subMinutes((int) config('analytics.dedup_minutes')))
            ->exists();
    }

    private function utm(Request $request, string $key): ?string
    {
        $value = $request->query($key);

        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        return Str::limit(Str::lower(trim($value)), 100, '');
    }

    private function resolveViewable(Request $request): ?Model
    {
        foreach (self::VIEWABLE_PARAMS as $param => $class) {
            $model = $request->route($param);
            if ($model instanceof Model && is_a($model, $class)) {
                return $model;
            }
        }

        return null;
    }

    private function shouldTrack(Request $request, Response $response): bool
    {
        if (! $request->isMethod('GET')) {
            return false;
        }

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        if (str_starts_with($request->path(), 'admin')) {
            return false;
        }

        if (str_starts_with($request->path(), 'livewire')) {
            return false;
        }

        return ! UserAgent::isBot($request->userAgent());
    }
}
