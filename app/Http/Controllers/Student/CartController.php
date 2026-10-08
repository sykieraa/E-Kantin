<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Support\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = Cart::detailed();
        $canteen = $cart['lines'][0]['product']->canteen ?? null;

        return view('student.cart.index', [
            'lines' => $cart['lines'],
            'total' => $cart['total'],
            'issues' => $cart['issues'],
            'canteen' => $canteen,
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:'.max($product->stock, 1)],
        ], [
            'quantity.max' => 'Jumlah melebihi stok yang tersedia.',
        ]);

        if (! $product->canteen->isAcceptingOrders()) {
            return back()->with('error', $product->canteen->closedMessage());
        }

        if (! $product->isOrderable()) {
            return back()->with('error', 'Menu sedang tidak tersedia.');
        }

        $switched = Cart::canteenId() !== null && Cart::canteenId() !== $product->canteen_id;

        Cart::add($product, (int) $data['quantity']);

        $message = $switched
            ? 'Keranjang diganti karena kamu berpindah kantin. '.$product->name.' sudah ditambahkan.'
            : $product->name.' ditambahkan. Kamu dapat memilih menu lain atau membuka keranjang dari navigasi bawah.';

        return back()->with('success', $message);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:'.max($product->stock, 0)],
        ], [
            'quantity.max' => 'Stok tidak mencukupi. Sisa stok: '.$product->stock.'.',
        ]);

        Cart::update($product->id, (int) $data['quantity']);

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        Cart::remove($product->id);

        return back()->with('success', 'Menu dikeluarkan dari keranjang.');
    }

    public function clear(): RedirectResponse
    {
        Cart::clear();

        return back()->with('success', 'Keranjang dikosongkan.');
    }
}
