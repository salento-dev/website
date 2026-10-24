<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventCardTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_without_end_date_is_past_only_after_it_starts(): void
    {
        $future = Event::factory()->make(['starts_at' => now()->addDays(3), 'ends_at' => null]);
        $past = Event::factory()->make(['starts_at' => now()->subDays(3), 'ends_at' => null]);

        $this->assertFalse($future->isPast());
        $this->assertTrue($past->isPast());
    }

    public function test_event_with_end_date_is_past_after_it_ends(): void
    {
        $ongoing = Event::factory()->make(['starts_at' => now()->subHour(), 'ends_at' => now()->addHour()]);
        $ended = Event::factory()->make(['starts_at' => now()->subHours(3), 'ends_at' => now()->subHour()]);

        $this->assertFalse($ongoing->isPast());
        $this->assertTrue($ended->isPast());
    }
}
