<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // total orders data
        $totalOrders = Order::count();

        // completed orders data
        $completedOrders = Order::completed()->count();

        $currentCompletedOrders = Order::completed()
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        $previousCompletedOrders = Order::completed()
            ->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->count();

        $ordersCompletedGrowth = percentGrowth($currentCompletedOrders, $previousCompletedOrders);

        // profit data
        $ordersProfit = DB::table('order_service')
            ->join('orders', 'orders.id', '=', 'order_service.order_id')
            ->join('services', 'services.id', '=', 'order_service.service_id')
            ->where('orders.status', OrderStatus::COMPLETED)
            ->sum('services.price');

        $currentProfit = DB::table('order_service')
            ->join('orders', 'orders.id', '=', 'order_service.order_id')
            ->join('services', 'services.id', '=', 'order_service.service_id')
            ->where('orders.status', OrderStatus::COMPLETED)
            ->whereBetween('orders.created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->sum('services.price');

        $previousProfit = DB::table('order_service')
            ->join('orders', 'orders.id', '=', 'order_service.order_id')
            ->join('services', 'services.id', '=', 'order_service.service_id')
            ->where('orders.status', OrderStatus::COMPLETED)
            ->whereBetween('orders.created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
            ->sum('services.price');

        $profitGrowth = percentGrowth($currentProfit, $previousProfit);

        return view('admin.pages.dashboard', compact('totalOrders', 'completedOrders', 'ordersProfit', 'ordersCompletedGrowth', 'profitGrowth'));
    }

    public function recentOrders(Request $request): JsonResponse
    {
        $status = $request->query('status');
        $search = $request->query('search');

        // when is needed to catch 0 satatus value
        $orders = Order::with('services')
            ->when(
                $status !== null && OrderStatus::tryFrom((int) $status),
                fn ($q) => $q->where('status', (int) $status)
            )
            ->when($search, fn ($q) => $q->where('names', 'like', "%{$search}%"))
            ->when($search, fn ($q) => $q->orWhere('phone', 'like', "%{$search}%"))
            ->latest()
            ->limit(5)
            ->get();

        return response()->json([
            'data' => $orders,
        ]);
    }
}
