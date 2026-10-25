<?php

declare(strict_types=1);

namespace App\Support\Analytics;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Cookieless visitor identifier: a hash of IP and user agent salted with a
 * random value that rotates every day and is then forgotten, so visits on
 * different days cannot be linked and the IP is never stored.
 */
class VisitorHash
{
    private const SALT_KEY_PREFIX = 'analytics:salt:';

    public static function fromRequest(Request $request): string
    {
        $payload = ($request->ip() ?? '').'|'.($request->userAgent() ?? '').'|'.$request->getHost();

        return substr(hash_hmac('sha256', $payload, self::todaySalt()), 0, 16);
    }

    public static function todaySalt(): string
    {
        $today = now()->toDateString();

        return Cache::remember(
            self::SALT_KEY_PREFIX.$today,
            now()->endOfDay()->addHour(),
            function () use ($today): string {
                self::forgetSaltsBefore($today);

                return Str::random(64);
            },
        );
    }

    public static function forgetSaltsBefore(string $date): void
    {
        foreach (range(1, 7) as $daysAgo) {
            Cache::forget(self::SALT_KEY_PREFIX.now()->parse($date)->subDays($daysAgo)->toDateString());
        }
    }
}
