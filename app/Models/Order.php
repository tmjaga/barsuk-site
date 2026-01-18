<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'names',
        'email',
        'phone',
        'order_start',
        'order_end',
        'status',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'order_start' => 'datetime',
        'order_end' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saved(function (Order $order) {
            $order->calculateOrderEnd();
        });
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }

    public function calculateOrderEnd(): void
    {
        if (! $this->order_start) {
            return;
        }

        $totalMinutes = (int) $this->services()->sum('duration');

        $newEnd = $this->order_start->copy()->addMinutes($totalMinutes);

        if (! $this->order_end || ! $this->order_end->equalTo($newEnd)) {
            $this->fill([
                'order_end' => $newEnd,
            ])->saveQuietly();
        }
    }

    // custom calendar_event attribute for FullCalendar
    public function getCalendarEventAttribute(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->names,
            'start' => $this->order_start->toIso8601String(),
            'end' => $this->order_end?->toIso8601String(),
            'extendedProps' => [
                'calendar' => $this->status->statusKey(),
                'email' => $this->email,
                'phone' => $this->phone,
            ],
        ];
    }
}
