<?php

namespace App\Actions\Order;

use App\Exceptions\OrderException;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Throwable;

class CreateOrderAction
{
    public function __invoke(array $data): Order
    {
        try {
            $order = Order::create($data);

            $order->services()->attach($data['services']);

            $order->refresh();
            $order->calculateOrderEnd();

            $order->save();

            return $order;
        } catch (Throwable $e) {
            Log::error('Error creating Order', [
                'message' => $e->getMessage(),
            ]);

            throw new OrderException(
                __('Error creating Order'),
                previous: $e
            );
        }
    }
}
