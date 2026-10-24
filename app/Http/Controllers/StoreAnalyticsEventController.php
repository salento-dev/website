<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnalyticsEventRequest;
use App\Models\AnalyticsEvent;
use App\Support\Analytics\UserAgent;
use App\Support\Analytics\ViewableResolver;
use App\Support\Analytics\VisitorHash;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class StoreAnalyticsEventController extends Controller
{
    public function __invoke(StoreAnalyticsEventRequest $request): Response
    {
        if (UserAgent::isBot($request->userAgent())) {
            return response()->noContent();
        }

        $url = $request->string('url')->toString();
        $path = $request->string('path')->toString();
        $viewable = ViewableResolver::fromPath($path);
        $targetHost = parse_url($url, PHP_URL_HOST);

        AnalyticsEvent::query()->create([
            'name' => $request->filled('name') ? $request->string('name')->toString() : $this->classify($url),
            'path' => $path,
            'viewable_type' => $viewable?->getMorphClass(),
            'viewable_id' => $viewable?->getKey(),
            'target_url' => $url,
            'target_host' => is_string($targetHost) ? Str::limit(strtolower($targetHost), 255, '') : null,
            'label' => $request->filled('label') ? $request->string('label')->toString() : null,
            'visitor_hash' => VisitorHash::fromRequest($request),
        ]);

        return response()->noContent();
    }

    private function classify(string $url): string
    {
        if (str_starts_with(strtolower($url), 'mailto:')) {
            return AnalyticsEvent::EMAIL;
        }

        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $host = str_starts_with($host, 'www.') ? substr($host, 4) : $host;

        /** @var array<string, string> $clickHosts */
        $clickHosts = config('analytics.click_hosts', []);

        foreach ($clickHosts as $knownHost => $name) {
            if ($host === $knownHost || str_ends_with($host, '.'.$knownHost)) {
                return $name;
            }
        }

        return AnalyticsEvent::OUTBOUND;
    }
}
