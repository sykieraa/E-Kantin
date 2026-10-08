<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::with('canteen', 'items')
            ->where('student_id', $request->user()->student?->id)
            ->latest('ordered_at')
            ->paginate(10);

        return view('student.orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order): View
    {
        $this->authorizeOwnership($request, $order);
        $order->load('canteen', 'items');

        // Berapa pesanan lain di kantin yang sama yang masih mengantre di depan.
        $ahead = Order::where('canteen_id', $order->canteen_id)
            ->whereDate('queue_date', $order->queue_date)
            ->where('queue_number', '<', $order->queue_number)
            ->whereIn('status', [Order::STATUS_PENDING, Order::STATUS_PROCESSING])
            ->count();

        return view('student.orders.show', compact('order', 'ahead'));
    }

    /**
     * Dipakai JavaScript untuk mengecek perubahan status secara berkala (polling).
     * Ini bukan websocket/push, melainkan permintaan berulang tiap beberapa detik.
     */
    public function status(Request $request, Order $order): JsonResponse
    {
        $this->authorizeOwnership($request, $order);

        return response()->json([
            'status' => $order->status,
            'label' => $order->statusLabel(),
        ]);
    }

    /**
     * Validasi ownership: siswa hanya boleh membuka pesanannya sendiri.
     */
    private function authorizeOwnership(Request $request, Order $order): void
    {
        abort_unless($order->student_id === $request->user()->student?->id, 403, 'Ini bukan pesanan kamu.');
    }
}
