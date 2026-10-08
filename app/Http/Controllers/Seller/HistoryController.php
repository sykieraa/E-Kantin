<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HistoryController extends Controller
{
    use Concerns;

    public function index(Request $request): View
    {
        $canteen = $this->canteen($request);

        // Format filter: YYYY-MM
        $month = $request->query('month', now()->format('Y-m'));
        
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = now()->format('Y-m');
        }

        $query = Order::with('student')
            ->where('canteen_id', $canteen->id)
            ->whereIn('status', [
                Order::STATUS_COMPLETED,
                Order::STATUS_CANCELLED,
            ])
            ->whereYear('ordered_at', substr($month, 0, 4))
            ->whereMonth('ordered_at', substr($month, 5, 2));

        // Total pendapatan hanya dari pesanan yang selesai
        $revenue = (clone $query)
            ->where('status', Order::STATUS_COMPLETED)
            ->sum('total');

        // Riwayat transaksi terbaru
        $orders = $query
            ->latest('ordered_at')
            ->paginate(20)
            ->withQueryString();

        return view('seller.history.index', compact(
            'canteen',
            'orders',
            'revenue',
            'month'
        ));
    }
}