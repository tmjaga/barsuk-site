<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Service;
use App\Services\BookingSlotService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Enum;
use Throwable;

class BookingController extends Controller
{
    public function index(): View
    {
        return view('pages.booking.booking-index');
    }

    public function services(): JsonResponse
    {
        $services = Service::where('active', 1)->get();

        return response()->json(['data' => $services]);
    }

    public function slots(Request $request, BookingSlotService $service): JsonResponse
    {
        $slots = $service->getDaySlots(
            $request->service_ids,
            $request->date
        );

        return response()->json(['data' => $slots]);
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
}
