<?php

declare(strict_types=1);

namespace App\Support\Analytics;

use Jaybizzle\CrawlerDetect\CrawlerDetect;

class UserAgent
{
    public const DEVICE_MOBILE = 'mobile';

    public const DEVICE_TABLET = 'tablet';

    public const DEVICE_DESKTOP = 'desktop';

    /** @var array<string, string> */
    public const DEVICE_LABELS = [
        self::DEVICE_MOBILE => 'Mobile',
        self::DEVICE_TABLET => 'Tablet',
        self::DEVICE_DESKTOP => 'Desktop',
    ];

    private const EXTRA_BOT_PATTERNS = [
        'lighthouse', 'headlesschrome', 'puppeteer', 'playwright', 'whatsapp', 'telegrambot',
    ];

    public static function isBot(?string $userAgent): bool
    {
        if (blank($userAgent)) {
            return true;
        }

        $normalized = strtolower($userAgent);

        foreach (self::EXTRA_BOT_PATTERNS as $pattern) {
            if (str_contains($normalized, $pattern)) {
                return true;
            }
        }

        return (new CrawlerDetect)->isCrawler($userAgent);
    }

    public static function device(?string $userAgent): string
    {
        $userAgent ??= '';

        if (preg_match('/iPad|Tablet|PlayBook|Silk|Kindle|Android(?!.*Mobile)/i', $userAgent) === 1) {
            return self::DEVICE_TABLET;
        }

        if (preg_match('/Mobi|iPhone|iPod|Android|Windows Phone|Opera Mini/i', $userAgent) === 1) {
            return self::DEVICE_MOBILE;
        }

        return self::DEVICE_DESKTOP;
    }
}
