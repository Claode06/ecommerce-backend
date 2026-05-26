<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\WarehouseStock;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $ordersToday = Order::whereDate('created_at', today())->count();

        $ordersByStatus = Order::selectRaw('status, COUNT(*) as total')
            ->whereDate('created_at', today())
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalRevenue = Order::where('status', 5)->sum('total');

        $activeCustomers = User::whereNull('deleted_at')->count();

        $lowStocks = WarehouseStock::with(['productVariant.product', 'warehouse'])
            ->where('quantity', '<', 5)
            ->where('quantity', '>', 0)
            ->orderBy('quantity')
            ->limit(10)
            ->get();

        return view('admin.dashboard.index', compact(
            'ordersToday', 'ordersByStatus', 'totalRevenue',
            'activeCustomers', 'lowStocks'
        ));
    }
}
