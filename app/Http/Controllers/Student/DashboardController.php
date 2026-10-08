<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Canteen;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));

        $canteens = Canteen::query()
            ->withCount(['products' => fn ($q) => $q->orderable()])
            ->when($search !== '', fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderByRaw("FIELD(status, 'open', 'temporarily_closed', 'closed')")
            ->orderBy('name')
            ->get();

        $student = $request->user()->student;

        // Pesanan yang masih berjalan, ditampilkan sebagai kartu antrean aktif.
        $activeOrders = Order::with('canteen')
            ->where('student_id', $student?->id)
            ->whereIn('status', [
                Order::STATUS_PENDING,
                Order::STATUS_PROCESSING,
                Order::STATUS_READY,
            ])
            ->latest('ordered_at')
            ->get();

        // Menu populer / rekomendasi dari kantin yang sedang buka.
        $popularProducts = Product::with('canteen')
            ->orderable()
            ->whereHas('canteen', fn ($q) => $q->where('status', Canteen::STATUS_OPEN))
            ->inRandomOrder()
            ->take(6)
            ->get();

        return view('student.dashboard', compact(
            'canteens',
            'activeOrders',
            'popularProducts',
            'search',
            'student'
        ));
    }
}