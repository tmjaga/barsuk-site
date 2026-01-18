<?php

namespace App\Enums;

enum OrderStatus: int
{
    case PENDING = 0;
    case CONFIRMED = 1;
    case REJECTED = 2;
    case COMPLETED = 3;

    public function title(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::CONFIRMED => 'Confirmed',
            self::REJECTED => 'Rejected',
            self::COMPLETED => 'Completed',
        };
    }

    public static function titles(): array
    {
        $titles = array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = $case->title();

            return $carry;
        }, []);

        asort($titles, SORT_NATURAL | SORT_FLAG_CASE);

        return $titles;
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => '#f59e0b',
            self::CONFIRMED => '#22c55e',
            self::REJECTED => '#ef4444',
            self::COMPLETED => '#60a5fa',
        };
    }

    public function statusKey(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::CONFIRMED => 'primary',
            self::REJECTED => 'danger',
            self::COMPLETED => 'success',
        };
    }
}
