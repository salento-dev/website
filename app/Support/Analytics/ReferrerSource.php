<?php

declare(strict_types=1);

namespace App\Support\Analytics;

class ReferrerSource
{
    /**
     * Reduce a referrer URL to a canonical source host, or null for direct
     * traffic and internal navigation.
     */
    public static function fromReferrer(?string $referrer, string $ownHost): ?string
    {
        if (blank($referrer)) {
            return null;
        }

        $host = parse_url($referrer, PHP_URL_HOST);

        if (! is_string($host) || $host === '') {
            return null;
        }

        $host = self::stripWww(strtolower($host));

        if ($host === self::stripWww(strtolower($ownHost))) {
            return null;
        }

        /** @var array<string, string> $aliases */
        $aliases = config('analytics.referrer_aliases', []);

        if (isset($aliases[$host])) {
            return $aliases[$host];
        }

        if (preg_match('/^google\.[a-z.]+$/', $host) === 1) {
            return 'google';
        }

        if (preg_match('/^(?:[a-z]{2}\.)?linkedin\.com$/', $host) === 1) {
            return 'linkedin.com';
        }

        return $host;
    }

    private static function stripWww(string $host): string
    {
        return str_starts_with($host, 'www.') ? substr($host, 4) : $host;
    }
}
