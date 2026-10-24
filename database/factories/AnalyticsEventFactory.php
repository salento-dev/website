<?php

namespace Database\Factories;

use App\Models\AnalyticsEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AnalyticsEvent>
 */
class AnalyticsEventFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => AnalyticsEvent::OUTBOUND,
            'path' => '/',
            'target_url' => fake()->url(),
            'target_host' => fake()->domainName(),
            'visitor_hash' => fake()->regexify('[a-f0-9]{16}'),
            'created_at' => now(),
        ];
    }

    public function whatsapp(): static
    {
        return $this->state(fn (): array => [
            'name' => AnalyticsEvent::WHATSAPP,
            'target_url' => 'https://chat.whatsapp.com/example',
            'target_host' => 'chat.whatsapp.com',
        ]);
    }
}
