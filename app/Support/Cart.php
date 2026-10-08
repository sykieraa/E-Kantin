<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

/**
 * Keranjang belanja sederhana yang disimpan di session.
 *
 * Bentuk data di session:
 *   ['canteen_id' => 1, 'items' => [productId => quantity]]
 *
 * Aturan: satu keranjang hanya boleh berisi menu dari SATU kantin,
 * karena satu pesanan (orders) hanya terhubung ke satu canteen_id.
 */
class Cart
{
    private const KEY = 'cart';

    public static function all(): array
    {
        return Session::get(self::KEY, ['canteen_id' => null, 'items' => []]);
    }

    public static function canteenId(): ?int
    {
        return self::all()['canteen_id'];
    }

    public static function items(): array
    {
        return self::all()['items'];
    }

    public static function isEmpty(): bool
    {
        return count(self::items()) === 0;
    }

    public static function totalQuantity(): int
    {
        return array_sum(self::items());
    }

    public static function add(Product $product, int $quantity): void
    {
        $cart = self::all();

        // Ganti keranjang bila siswa berpindah kantin.
        if ($cart['canteen_id'] !== null && $cart['canteen_id'] !== $product->canteen_id) {
            $cart = ['canteen_id' => null, 'items' => []];
        }

        $cart['canteen_id'] = $product->canteen_id;
        $current = $cart['items'][$product->id] ?? 0;
        $cart['items'][$product->id] = min($current + $quantity, $product->stock);

        Session::put(self::KEY, $cart);
    }

    public static function update(int $productId, int $quantity): void
    {
        $cart = self::all();

        if ($quantity <= 0) {
            unset($cart['items'][$productId]);
        } else {
            $cart['items'][$productId] = $quantity;
        }

        if (count($cart['items']) === 0) {
            $cart['canteen_id'] = null;
        }

        Session::put(self::KEY, $cart);
    }

    public static function remove(int $productId): void
    {
        self::update($productId, 0);
    }

    public static function clear(): void
    {
        Session::forget(self::KEY);
    }

    /**
     * Ambil produk di keranjang beserta perhitungan subtotal dan catatan masalah
     * (stok kurang / menu dinonaktifkan). Dipakai halaman keranjang & checkout.
     *
     * @return array{lines: array, total: int, issues: array}
     */
    public static function detailed(): array
    {
        $items = self::items();

        if (count($items) === 0) {
            return ['lines' => [], 'total' => 0, 'issues' => []];
        }

        $products = Product::with('canteen')->whereIn('id', array_keys($items))->get()->keyBy('id');

        $lines = [];
        $total = 0;
        $issues = [];

        foreach ($items as $productId => $quantity) {
            $product = $products->get($productId);

            if (! $product) {
                $issues[] = 'Ada menu yang sudah dihapus penjual dan dikeluarkan dari keranjang.';
                self::remove((int) $productId);

                continue;
            }

            if (! $product->is_available) {
                $issues[] = "Menu {$product->name} sedang tidak tersedia.";
            } elseif ($product->stock < $quantity) {
                $issues[] = "Stok {$product->name} tinggal {$product->stock}.";
            }

            $subtotal = $product->price * $quantity;
            $total += $subtotal;

            $lines[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];
        }

        return ['lines' => $lines, 'total' => $total, 'issues' => $issues];
    }
}
