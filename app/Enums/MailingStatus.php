<?php

namespace App\Enums;

enum MailingStatus: int
{
    case PENDING = 0;
    case IN_PROGRESS = 1;
    case COMPLETED = 2;

    public function title(): string
    {
        return match ($this) {
            self::PENDING => __('Pending'),
            self::IN_PROGRESS => __('In Progress'),
            self::COMPLETED => __('Completed'),
        };
    }
}
