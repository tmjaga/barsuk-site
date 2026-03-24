<?php

namespace App\Models;

use App\Enums\MailingStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Mailing extends Model
{
    protected $fillable = ['subject', 'body', 'total', 'sent', 'failed', 'is_completed', 'started_at'];

    protected $appends = ['formatted_starting_date', 'status'];

    protected $casts = [
        'started_at' => 'datetime',
    ];

    protected function status(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->is_completed && ! $this->started_at) {
                    return MailingStatus::COMPLETED->value;
                }

                if (! $this->is_completed && $this->started_at) {
                    return MailingStatus::IN_PROGRESS->value;
                }

                return MailingStatus::PENDING->value;
            }
        );
    }

    public function getProgressAttribute(): float
    {
        if ($this->total === 0) {
            return 0;
        }

        return round(($this->sent + $this->failed) / $this->total * 100, 2);
    }

    public function getFormattedStartingDateAttribute(): string
    {
        return $this->started_at ? $this->started_at->format('d.m.Y H:i') : '';
    }
}
