<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(): View
    {
        $allServices = Service::select('id', 'title')->orderBy('title')->get();
        $orderStatuses = OrderStatus::titles();

        return view('admin.calendar.calendar-index', compact('allServices', 'orderStatuses'));
    }

    public function events(Request $request): JsonResponse
    {
        $start = $request->query('start');
        $end = $request->query('end');
        $orders = Order::whereBetween('order_start', [$start, $end])->get();
        $events = $orders->pluck('calendar_event');
        //dd($events);

        return response()->json($events);
    }
}
