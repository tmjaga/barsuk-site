<?php

namespace App\Enums;

enum OrderStatus: int
{
    case PENDING = 0;
    case CONFIRMED = 1;
    case REJECTED = 2;
    case COMPLEATED = 3;

    public function title(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::CONFIRMED => 'Confirmed',
            self::REJECTED => 'Rejected',
            self::COMPLEATED => 'Completed',
        };
    }

    public static function titles(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = $case->title();

            return $carry;
        }, []);
    }
}
