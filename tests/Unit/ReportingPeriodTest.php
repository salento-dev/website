<?php

namespace Tests\Unit;

use App\Support\Analytics\ReportingPeriod;
use Carbon\CarbonImmutable;
use Tests\TestCase;

class ReportingPeriodTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->travelTo(CarbonImmutable::parse('2026-10-25 15:00:00'));
    }

    public function test_defaults_to_the_last_thirty_days_including_today(): void
    {
        $period = ReportingPeriod::fromFilters(null);

        $this->assertSame('2026-09-26 00:00:00', $period->start->toDateTimeString());
        $this->assertSame('2026-10-25 23:59:59', $period->end->toDateTimeString());
        $this->assertSame(30, $period->days());
    }

    public function test_the_previous_period_has_the_same_length_and_ends_before_the_current_one(): void
    {
        $previous = ReportingPeriod::fromFilters(['period' => '7'])->previous();

        $this->assertSame('2026-10-12 00:00:00', $previous->start->toDateTimeString());
        $this->assertSame('2026-10-18 23:59:59', $previous->end->toDateTimeString());
        $this->assertSame(7, $previous->days());
    }

    public function test_a_custom_range_is_used_when_valid(): void
    {
        $period = ReportingPeriod::fromFilters(['period' => 'custom', 'startDate' => '2026-10-01', 'endDate' => '2026-10-10']);

        $this->assertSame('2026-10-01 00:00:00', $period->start->toDateTimeString());
        $this->assertSame('2026-10-10 23:59:59', $period->end->toDateTimeString());
        $this->assertSame(10, $period->days());
    }

    public function test_an_invalid_custom_range_falls_back_to_the_default(): void
    {
        $inverted = ReportingPeriod::fromFilters(['period' => 'custom', 'startDate' => '2026-10-10', 'endDate' => '2026-10-01']);
        $garbage = ReportingPeriod::fromFilters(['period' => 'custom', 'startDate' => 'not-a-date']);
        $unknown = ReportingPeriod::fromFilters(['period' => '9999']);

        foreach ([$inverted, $garbage, $unknown] as $period) {
            $this->assertSame(30, $period->days());
        }
    }
}
