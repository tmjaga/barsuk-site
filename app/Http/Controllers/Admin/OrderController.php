<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Order\CreateOrderAction;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Service;
use App\Notifications\ChangeOrderNotification;
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

        // when is needed to catch 0 satatus value
        $orders = Order::with('services')
            ->when(
                $status !== null && OrderStatus::tryFrom((int) $status),
                fn ($q) => $q->where('status', (int) $status)
            )
            ->latest()
            ->paginate(config('app.items_per_page'))
            ->withQueryString();

        if ($request->ajax()) {
            return response()->json($orders);
        }

        $allServices = Service::select('id', 'title')->orderBy('title')->get();
        $orderStatuses = OrderStatus::titles();

        return view('admin.orders.order-index', compact('orders', 'orderStatuses', 'status', 'allServices'));
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

    public function update(StoreOrderRequest $request, Order $order): JsonResponse
    {
        try {
            $order->update($request->validated());

            // update order services
            $order->services()->sync($request->validated()['services']);

            // calculate order_end
            $order->refresh();
            $order->calculateOrderEnd();
            $order->save();

            // send notification to customer if order rejected
            if ($order->wasChanged('status') && $order->status == OrderStatus::REJECTED) {
                $order->load('services')
                    ->loadSum('services', 'duration')
                    ->loadSum('services', 'price');

                $data = [
                    'order' => $order->toArray(),
                ];

                $order->notify(new ChangeOrderNotification($data));
            }

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

    public function store(StoreOrderRequest $request, CreateOrderAction $createOrderAction): JsonResponse
    {
        $createOrderAction($request->validated());

        return response()->json([
            'message' => __('Order created successfully'),
        ], 201);
    }

    public function destroy(Order $order): JsonResponse
    {
        try {
            $orderStatus = $order->status;
            $order->load('services')
                ->loadSum('services', 'duration')
                ->loadSum('services', 'price');

            $data = [
                'order' => $order->toArray(),
            ];

            $order->services()->detach();
            $order->delete();

            if (in_array($orderStatus, [OrderStatus::PENDING, OrderStatus::CONFIRMED])) {
                $order->notify(new ChangeOrderNotification($data));
            }

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
