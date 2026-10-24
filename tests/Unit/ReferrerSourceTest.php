<?php

namespace Tests\Unit;

use App\Support\Analytics\ReferrerSource;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ReferrerSourceTest extends TestCase
{
    /**
     * @return array<string, array{?string, ?string}>
     */
    public static function referrerProvider(): array
    {
        return [
            'no referrer' => [null, null],
            'empty referrer' => ['', null],
            'internal navigation' => ['https://salento.dev/events', null],
            'internal with www' => ['https://www.salento.dev/', null],
            'instagram redirect' => ['https://l.instagram.com/?u=x', 'instagram.com'],
            'linkedin country subdomain' => ['https://it.linkedin.com/feed/', 'linkedin.com'],
            'linkedin android app' => ['android-app://com.linkedin.android/', 'linkedin.com'],
            'google search' => ['https://www.google.it/', 'google'],
            'twitter short link' => ['https://t.co/abc', 'x.com'],
            'facebook mobile' => ['https://m.facebook.com/', 'facebook.com'],
            'other site strips www' => ['https://www.gdglecce.it/eventi', 'gdglecce.it'],
            'uppercase host' => ['https://GitHub.com/salento-dev', 'github.com'],
            'garbage' => ['not a url', null],
        ];
    }

    #[DataProvider('referrerProvider')]
    public function test_referrers_are_reduced_to_a_canonical_source(?string $referrer, ?string $expected): void
    {
        $this->assertSame($expected, ReferrerSource::fromReferrer($referrer, 'salento.dev'));
    }
}
