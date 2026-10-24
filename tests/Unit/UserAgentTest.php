<?php

namespace Tests\Unit;

use App\Support\Analytics\UserAgent;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class UserAgentTest extends TestCase
{
    /**
     * @return array<string, array{string, string}>
     */
    public static function deviceProvider(): array
    {
        return [
            'iphone' => ['Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1', UserAgent::DEVICE_MOBILE],
            'android phone' => ['Mozilla/5.0 (Linux; Android 14; Pixel 8) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Mobile Safari/537.36', UserAgent::DEVICE_MOBILE],
            'ipad' => ['Mozilla/5.0 (iPad; CPU OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1', UserAgent::DEVICE_TABLET],
            'android tablet' => ['Mozilla/5.0 (Linux; Android 14; SM-X710) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36', UserAgent::DEVICE_TABLET],
            'mac' => ['Mozilla/5.0 (Macintosh; Intel Mac OS X 14_0) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Safari/605.1.15', UserAgent::DEVICE_DESKTOP],
            'windows' => ['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36', UserAgent::DEVICE_DESKTOP],
        ];
    }

    #[DataProvider('deviceProvider')]
    public function test_device_is_detected_from_the_user_agent(string $userAgent, string $expected): void
    {
        $this->assertSame($expected, UserAgent::device($userAgent));
    }

    /**
     * @return array<string, array{?string, bool}>
     */
    public static function botProvider(): array
    {
        return [
            'googlebot' => ['Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', true],
            'ahrefs' => ['Mozilla/5.0 (compatible; AhrefsBot/7.0; +http://ahrefs.com/robot/)', true],
            'whatsapp preview' => ['WhatsApp/2.23.20.0', true],
            'lighthouse' => ['Mozilla/5.0 (Linux; Android 11; moto g power (2022)) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0 Mobile Safari/537.36 Chrome-Lighthouse', true],
            'headless chrome' => ['Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/120.0 Safari/537.36', true],
            'curl' => ['curl/8.4.0', true],
            'empty' => ['', true],
            'null' => [null, true],
            'safari' => ['Mozilla/5.0 (Macintosh; Intel Mac OS X 14_0) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Safari/605.1.15', false],
            'chrome android' => ['Mozilla/5.0 (Linux; Android 14; Pixel 8) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Mobile Safari/537.36', false],
        ];
    }

    #[DataProvider('botProvider')]
    public function test_bots_are_detected(?string $userAgent, bool $expected): void
    {
        $this->assertSame($expected, UserAgent::isBot($userAgent));
    }
}
