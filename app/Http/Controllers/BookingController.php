<?php

namespace App\Http\Controllers;

use App\Actions\Order\CreateOrderAction;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Service;
use App\Services\BookingSlotService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function store(StoreOrderRequest $request, CreateOrderAction $createOrderAction): JsonResponse
    {
        $createOrderAction($request->validated());

        return response()->json([
            'message' => __('Booking created successfully'),
        ], 201);
    }
}
