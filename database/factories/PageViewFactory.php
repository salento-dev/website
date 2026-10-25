<?php

namespace Database\Factories;

use App\Models\PageView;
use App\Support\Analytics\UserAgent;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Factory<PageView>
 */
class PageViewFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $path = fake()->randomElement(['/', '/events', '/resources', '/partners', '/stories']);

        return [
            'path' => $path,
            'url' => 'http://localhost'.$path,
            'visitor_hash' => fake()->regexify('[a-f0-9]{16}'),
            'referrer' => null,
            'referrer_host' => null,
            'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 14_0) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Safari/605.1.15',
            'device' => UserAgent::DEVICE_DESKTOP,
            'created_at' => now(),
        ];
    }

    public function viewing(Model $viewable, string $path): static
    {
        return $this->state(fn (): array => [
            'path' => $path,
            'url' => 'http://localhost'.$path,
            'viewable_type' => $viewable->getMorphClass(),
            'viewable_id' => $viewable->getKey(),
        ]);
    }
}
