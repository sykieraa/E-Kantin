<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Canteen;
use App\Models\Order;
use App\Models\Product;
use App\Models\Student;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'students' => Student::count(),
            'canteens' => Canteen::count(),
            'products' => Product::count(),
            'orders_today' => Order::whereDate('ordered_at', today())->count(),
            'revenue_today' => Order::whereDate('ordered_at', today())
                ->where('status', Order::STATUS_COMPLETED)->sum('total'),
            'open' => Canteen::where('status', Canteen::STATUS_OPEN)->count(),
            'closed' => Canteen::where('status', '!=', Canteen::STATUS_OPEN)->count(),
        ];

        $canteens = Canteen::with('seller')->withCount('products')->orderBy('name')->get();

        return view('admin.dashboard', compact('stats', 'canteens'));
    }
}
