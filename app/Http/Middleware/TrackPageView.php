<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    private const DEDUP_MINUTES = 30;

    private const SESSION_KEY = 'pv_seen';

    private const REQUEST_KEY = '_track_pv';

    /** @var array<string, class-string<Model>> */
    private const VIEWABLE_PARAMS = [
        'event' => \App\Models\Event::class,
        'story' => \App\Models\Story::class,
    ];

    private const BOT_PATTERNS = [
        'bot', 'crawl', 'spider', 'slurp', 'facebookexternalhit',
        'twitterbot', 'linkedinbot', 'whatsapp', 'telegrambot',
        'googlebot', 'bingbot', 'yandexbot', 'duckduckbot',
        'lighthouse', 'headlesschrome', 'puppeteer', 'playwright',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->shouldTrack($request, $response)) {
            return $response;
        }

        $path = $request->path() === '/' ? '/' : '/'.$request->path();

        if ($this->recentlyVisited($request, $path)) {
            return $response;
        }

        $this->markVisited($request, $path);

        $request->attributes->set(self::REQUEST_KEY, [
            'path' => $path,
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

        /** @var Model|null $viewable */
        $viewable = $data['viewable'];

        PageView::create([
            'path' => $data['path'],
            'url' => $request->fullUrl(),
            'referrer' => $request->headers->get('referer'),
            'user_agent' => $request->userAgent(),
            'viewable_type' => $viewable ? $viewable->getMorphClass() : null,
            'viewable_id' => $viewable?->getKey(),
        ]);
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

    private function recentlyVisited(Request $request, string $path): bool
    {
        $seen = $request->session()->get(self::SESSION_KEY, []);
        $lastSeen = $seen[$path] ?? null;

        return $lastSeen !== null && (time() - $lastSeen) < self::DEDUP_MINUTES * 60;
    }

    private function markVisited(Request $request, string $path): void
    {
        $seen = $request->session()->get(self::SESSION_KEY, []);
        $seen[$path] = time();
        $request->session()->put(self::SESSION_KEY, $seen);
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

        $userAgent = strtolower($request->userAgent() ?? '');

        foreach (self::BOT_PATTERNS as $pattern) {
            if (str_contains($userAgent, $pattern)) {
                return false;
            }
        }

        return true;
    }
}
