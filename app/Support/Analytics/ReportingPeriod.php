<?php

declare(strict_types=1);

namespace App\Support\Analytics;

use Carbon\CarbonImmutable;
use Throwable;

/**
 * Reporting period chosen in the admin dashboard filters.
 */
class ReportingPeriod
{
    public const DEFAULT = '30';

    public const CUSTOM = 'custom';

    /** @var array<string, string> */
    public const OPTIONS = [
        '7' => 'Ultimi 7 giorni',
        '30' => 'Ultimi 30 giorni',
        '90' => 'Ultimi 90 giorni',
        '365' => 'Ultimo anno',
        self::CUSTOM => 'Personalizzato',
    ];

    public function __construct(
        public readonly CarbonImmutable $start,
        public readonly CarbonImmutable $end,
    ) {}

    /**
     * @param  array<string, mixed>|null  $filters
     */
    public static function fromFilters(?array $filters): self
    {
        $period = (string) ($filters['period'] ?? self::DEFAULT);
        $today = CarbonImmutable::now()->endOfDay();

        if ($period === self::CUSTOM) {
            $start = self::parseDate($filters['startDate'] ?? null)?->startOfDay();
            $end = self::parseDate($filters['endDate'] ?? null)?->endOfDay() ?? $today;

            if ($start !== null && $start->lessThanOrEqualTo($end)) {
                return new self($start, $end);
            }

            $period = self::DEFAULT;
        }

        $days = array_key_exists($period, self::OPTIONS) ? (int) $period : (int) self::DEFAULT;

        return new self($today->subDays($days - 1)->startOfDay(), $today);
    }

    public function days(): int
    {
        return (int) $this->start->diffInDays($this->end->addSecond());
    }

    /**
     * The period of the same length that ends right before this one starts.
     */
    public function previous(): self
    {
        return new self($this->start->subDays($this->days()), $this->start->subSecond());
    }

    /**
     * @return array{0: CarbonImmutable, 1: CarbonImmutable}
     */
    public function bounds(): array
    {
        return [$this->start, $this->end];
    }

    private static function parseDate(mixed $value): ?CarbonImmutable
    {
        if (blank($value)) {
            return null;
        }

        try {
            return CarbonImmutable::parse((string) $value);
        } catch (Throwable) {
            return null;
        }
    }
}
