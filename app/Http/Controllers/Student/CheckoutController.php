<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Canteen;
use App\Models\Order;
use App\Services\OrderService;
use App\Support\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (Cart::isEmpty()) {
            return redirect()->route('student.cart.index')->with('error', 'Keranjang masih kosong.');
        }

        $cart = Cart::detailed();
        $canteen = Canteen::find(Cart::canteenId());

        return view('student.checkout.index', [
            'lines' => $cart['lines'],
            'total' => $cart['total'],
            'issues' => $cart['issues'],
            'canteen' => $canteen,
        ]);
    }

    public function store(Request $request, OrderService $orders): RedirectResponse
{
    $student = $request->user()->student;

    if (! $student) {
        return redirect()
            ->route('student.dashboard')
            ->with('error', 'Data siswa tidak ditemukan.');
    }

    $data = $request->validate([
        'payment_method' => [
            'required',
            'in:' . implode(',', array_keys(Order::paymentMethodOptions())),
        ],
    ]);

    try {
        $order = $orders->checkout(
            $student,
            $data['payment_method']
        );
    } catch (ValidationException $e) {
        return back()
            ->withErrors($e->errors())
            ->withInput();
    }

    return redirect()
        ->route('student.orders.show', $order)
        ->with(
            'success',
            'Pesanan berhasil dibuat. Nomor antrean kamu ' . $order->queue_code . '.'
        );
}
}
