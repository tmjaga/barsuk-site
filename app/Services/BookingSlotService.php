<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Service;
use Carbon\Carbon;

class BookingSlotService
{
    public function __construct() {}

    public function getDaySlots(array $serviceIds, string $date): array
    {
        $date = Carbon::parse($date)->timezone(config('app.timezone'));

        $duration = (int) Service::whereIn('id', $serviceIds)->sum('duration');
        $break = (int) config('booking.break_minutes');

        $workFrom = $date->copy()->setTimeFromTimeString(config('booking.work_from'));
        $workTo = $date->copy()->setTimeFromTimeString(config('booking.work_to'));

        $step = (int) config('booking.slot_step_minutes');

        $orders = Order::whereDate('order_start', $date)->get();

        $result = [];

        for ($time = $workFrom->copy();
            $time->copy()->addMinutes($duration)->lte($workTo);
            $time->addMinutes($step)) {

            $start = $time->copy();
            $end = $start->copy()->addMinutes($duration);

            $busy = $orders->contains(function ($order) use ($start, $end, $break) {
                return $start->lt($order->order_end->copy()->addMinutes($break))
                    && $end->gt($order->order_start->copy()->subMinutes($break));
            });

            $available = ! $busy && $start->isFuture();

            $result[] = [
                'time' => $start->format('H:i'),
                'available' => $available,
            ];
        }

        return $result;
    }
}
