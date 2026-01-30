<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
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
            ->latest()
            ->paginate(config('app.items_per_page'))
            ->withQueryString();

        //dd($orders->toArray());
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

    public function update(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'names' => 'required',
            'email' => 'required|email',
            'order_date' => 'required|date',
            'order_time' => 'required|date_format:H:i',
            'phone' => 'required|regex:/^\+?[0-9]+$/|min:10',
            'status' => ['required', new Enum(OrderStatus::class)],
            'services' => 'required|array|min:1',
        ]);

        try {
            $orderDateTime = Carbon::parse($validated['order_date'].' '.$validated['order_time'])->format('Y-m-d H:i:s');
            $validated['order_start'] = $orderDateTime;

            $order->update($validated);

            // update order services
            $order->services()->sync($validated['services']);

            // calculate order_end
            $order->refresh();
            $order->calculateOrderEnd();
            $order->save();

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

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'names' => 'required',
            'email' => 'required|email',
            'order_date' => 'required|date',
            'order_time' => 'required|date_format:H:i',
            'phone' => 'required|regex:/^\+?[0-9]+$/|min:10',
            'status' => ['required', new Enum(OrderStatus::class)],
            'services' => 'required|array|min:1',
        ]);

        try {
            $orderDateTime = Carbon::parse($validated['order_date'].' '.$validated['order_time'])->format('Y-m-d H:i:s');
            $validated['order_start'] = $orderDateTime;

            $order = Order::create($validated);

            // attach order services
            $order->services()->attach($validated['services']);

            // calculate order_end
            $order->refresh();
            $order->calculateOrderEnd();
            $order->save();

            return response()->json([
                'message' => __('Order created successfully'),
            ], 201);
        } catch (Throwable $e) {
            Log::error('Error creating Order', [
                'message' => $e->getMessage(),
            ]);

            return response()->json(['message' => __('Error creating Order')], 500);
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
