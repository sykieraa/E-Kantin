<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    use Concerns;

    public function index(Request $request): View
    {
        $canteen = $this->canteen($request);

        $counts = Order::where('canteen_id', $canteen->id)
            ->whereDate('queue_date', today())
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $revenueToday = Order::where('canteen_id', $canteen->id)
            ->whereDate('queue_date', today())
            ->where('status', Order::STATUS_COMPLETED)
            ->sum('total');

        $lowStock = $canteen->products()->lowStock(5)->orderBy('stock')->limit(10)->get();

        $latestOrders = Order::with('student')
            ->where('canteen_id', $canteen->id)
            ->latest('ordered_at')
            ->limit(8)
            ->get();

        return view('seller.dashboard', compact('canteen', 'counts', 'revenueToday', 'lowStock', 'latestOrders'));
    }
}
