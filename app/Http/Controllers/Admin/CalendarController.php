<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(): View
    {
        // dd('Calendar');
        return view('admin.calendar.calendar-index');
    }

    public function events(Request $request): JsonResponse
    {
        $start = $request->query('start');
        $end   = $request->query('end');
        $orders = Order::whereBetween('order_start', [$start, $end])->get();
        $events = $orders->pluck('calendar_event');

        return response()->json($events);
    }}
