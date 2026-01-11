<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
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

    public function edit(string $id): JsonResponse
    {
        try {
            $order = Order::with('services')->findOrFail($id);

            return response()->json($order);
        } catch (Throwable $e) {
            Log::error('Order not found', [
                'message' => $e->getMessage(),
            ]);

            return response()->json(['message' => __('Order not found')], 404);
        }
    }

    public function update(Request $request, Order $order): JsonResponse
    {
        try {
            $validated = $request->validate([
                'order_date' => 'required|date',
                'order_time' => 'required|date_format:H:i',
                'status' => ['required', new Enum(OrderStatus::class)],
            ]);

            $orderDateTime = Carbon::parse($validated['order_date'].' '.$validated['order_time']);

            $order->update([
                'order_date' => $orderDateTime,
                'status' => $validated['status'],
            ]);

            return response()->json([
                'message' => __('Order updated successfully'),
            ]);
        } catch (Throwable $e) {
            Log::error('Order update error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => __('Error updating Order'),
            ], 500);
        }
    }

    public function destroy(Order $order): JsonResponse
    {
        try {
            $order->services()->detach();
            $order->delete();

            return response()->json([
                'message' => __('Order deleted successfully'),
            ]);

        } catch (Throwable $e) {
            Log::error('Error deleting Order', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => __('Error while deleting Order'),
            ], 500);
        }
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
