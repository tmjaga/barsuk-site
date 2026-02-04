<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
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

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', OrderStatus::COMPLETED);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
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

        if ($totalMinutes <= 0) {
            return;
        }

        $newEnd = $this->order_start->copy()->addMinutes($totalMinutes);
        $this->order_end = $newEnd;
    }

    // custom calendar_event attribute for FullCalendar
    public function getCalendarEventAttribute(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->names,
            'start' => $this->order_start->format('Y-m-d\TH:i:s'),
            'end' => $this->order_end->format('Y-m-d\TH:i:s'),
            'extendedProps' => [
                'calendar' => $this->status->statusKey(),
                'email' => $this->email,
                'phone' => $this->phone,
            ],
        ];
    }
}
