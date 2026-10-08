<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Canteen;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::with('student', 'canteen')
            ->when($request->query('status'), fn ($q, $s) => $q->where('status', $s))
            ->when($request->query('canteen'), fn ($q, $c) => $q->where('canteen_id', $c))
            ->when($request->query('date'), fn ($q, $d) => $q->whereDate('ordered_at', $d))
            ->latest('ordered_at')
            ->paginate(20)->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'canteens' => Canteen::orderBy('name')->get(),
        ]);
    }

    public function show(Order $order): View
    {
        $order->load('items', 'student', 'canteen');

        return view('admin.orders.show', compact('order'));
    }
}
