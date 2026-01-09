<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Enum;
use Throwable;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse|View
    {
        $status = $request->query('status');

        // when to catch 0 satatus value
        $orders = Order::with('services')
            ->when(
                $status !== null && OrderStatus::tryFrom((int) $status),
                fn ($q) => $q->where('status', (int) $status)
            )
            ->latest('order_date')
            ->paginate(config('app.items_per_page'))
            ->withQueryString();

        if ($request->ajax()) {
            return response()->json($orders);
        }

        $orderStatuses = OrderStatus::titles();

        return view('admin.orders.order-index', compact('orders', 'orderStatuses', 'status'));
    }

    public function updateStatus(int $orderId, Request $request): JsonResponse
    {
        try {
            $order = Order::findOrFail($orderId);

            $validated = $request->validate([
                'status' => ['required', new Enum(OrderStatus::class)],
            ]);

            $order->update([
                'status' => OrderStatus::from($validated['status']),
            ]);

            return response()->json([
                'message' => __('Service Status changed successfully'),
            ]);
        } catch (Throwable $e) {
            Log::error('Error updating Service Status', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => __('Error updating Service Status'),
            ], 500);
        }
    }
}
