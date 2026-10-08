<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    use Concerns;

    public function index(Request $request): View
    {
        $canteen = $this->canteen($request);
        $status = $request->query('status');

        $orders = Order::with('student', 'items')
            ->where('canteen_id', $canteen->id)
            ->when($status, fn ($q) => $q->where('status', $status))
            ->whereIn('status', [Order::STATUS_PENDING, Order::STATUS_PROCESSING, Order::STATUS_READY])
            ->orderBy('queue_date')
            ->orderBy('queue_number')
            ->paginate(15)
            ->withQueryString();

        return view('seller.orders.index', compact('canteen', 'orders', 'status'));
    }

    public function show(Request $request, Order $order): View
    {
        $this->authorizeOrder($request, $order);
        $order->load('items', 'student');

        return view('seller.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order, OrderService $orders): RedirectResponse
    {
        $this->authorizeOrder($request, $order);

        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(Order::statusOptions()))],
        ]);

        if (! in_array($data['status'], $order->allowedNextStatuses(), true)) {
            return back()->with('error', 'Perubahan status tidak diizinkan dari status saat ini.');
        }

        if ($data['status'] === Order::STATUS_CANCELLED) {
            try {
                $orders->cancel($order, 'Dibatalkan oleh penjual.');
            } catch (ValidationException $e) {
                return back()->with('error', $e->validator->errors()->first());
            }

            return back()->with('success', 'Pesanan dibatalkan. Saldo dan stok dikembalikan sesuai metode pembayaran.');
        }

        $update = ['status' => $data['status']];

        // Pesanan selesai dianggap sudah dibayar (pembayaran tunai saat pengambilan).
        if ($data['status'] === Order::STATUS_COMPLETED) {
            $update['payment_status'] = 'paid';
        }

        $order->update($update);

        return back()->with('success', 'Status pesanan '.$order->queue_code.' diperbarui.');
    }

    private function authorizeOrder(Request $request, Order $order): void
    {
        abort_unless($order->canteen_id === $this->canteen($request)->id, 403, 'Pesanan ini bukan milik kantin kamu.');
    }
}
