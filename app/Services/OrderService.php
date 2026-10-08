<?php

namespace App\Services;

use App\Models\Canteen;
use App\Models\Order;
use App\Models\Product;
use App\Models\Student;
use App\Support\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Semua logika checkout dikumpulkan di sini supaya controller tetap tipis
 * dan aturan bisnis (stok, antrean) hanya ditulis di satu tempat.
 */
class OrderService
{
    /**
     * Membuat pesanan dari isi keranjang.
     *
     * Seluruh proses dibungkus database transaction.
     * Baris produk dikunci dengan lockForUpdate() agar dua siswa
     * yang checkout bersamaan tidak bisa membuat stok menjadi minus.
     *
     * @throws ValidationException
     */
    public function checkout(
    Student $student,
    string $paymentMethod = Order::PAYMENT_OUTLET
): Order {
    $items = Cart::items();
    $canteenId = Cart::canteenId();

    if (count($items) === 0 || ! $canteenId) {
        throw ValidationException::withMessages([
            'cart' => 'Keranjang masih kosong.',
        ]);
    }

    if (! array_key_exists($paymentMethod, Order::paymentMethodOptions())) {
        throw ValidationException::withMessages([
            'payment_method' => 'Metode pembayaran tidak valid.',
        ]);
    }

    return DB::transaction(function () use (
        $student,
        $items,
        $canteenId,
        $paymentMethod
    ) {
        /*
         * Kunci data siswa selama transaksi.
         * Penting supaya saldo tidak bisa dipakai untuk
         * dua checkout bersamaan.
         */
        $student = Student::lockForUpdate()->findOrFail($student->id);

        /** @var Canteen $canteen */
        $canteen = Canteen::lockForUpdate()->findOrFail($canteenId);

        if (! $canteen->isAcceptingOrders()) {
            throw ValidationException::withMessages([
                'cart' => $canteen->closedMessage(),
            ]);
        }

        // Kunci semua produk yang ada di keranjang.
        $products = Product::whereIn('id', array_keys($items))
            ->where('canteen_id', $canteen->id)
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        $total = 0;
        $rows = [];

        foreach ($items as $productId => $quantity) {
            $product = $products->get($productId);

            if (! $product) {
                throw ValidationException::withMessages([
                    'cart' => 'Ada menu yang sudah tidak tersedia di kantin ini.',
                ]);
            }

            if (! $product->is_available) {
                throw ValidationException::withMessages([
                    'cart' => "Menu {$product->name} sedang tidak tersedia.",
                ]);
            }

            if ($product->stock < $quantity) {
                throw ValidationException::withMessages([
                    'cart' => "Stok tidak mencukupi untuk {$product->name}. Sisa stok: {$product->stock}.",
                ]);
            }

            $subtotal = $product->price * $quantity;
            $total += $subtotal;

            $rows[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'price' => $product->price,
                'subtotal' => $subtotal,
            ];
        }

        /*
         * Pembayaran menggunakan Saldo E-Kantin.
         */
        if ($paymentMethod === Order::PAYMENT_BALANCE) {
            if ($student->balance < $total) {
                throw ValidationException::withMessages([
                    'payment_method' => 'Saldo E-Kantin tidak mencukupi. Saldo kamu ' .
                        rupiah($student->balance) .
                        ', sedangkan total pesanan ' .
                        rupiah($total) .
                        '.',
                ]);
            }

            $student->decrement('balance', $total);
        }

        /*
         * Kurangi stok setelah semua validasi berhasil.
         */
        foreach ($items as $productId => $quantity) {
            $products->get($productId)->decrement('stock', $quantity);
        }

        $queue = $this->nextQueueNumber($canteen);

        $order = Order::create([
            'student_id' => $student->id,
            'canteen_id' => $canteen->id,
            'order_number' => sprintf(
                '%s-%s-%03d',
                $canteen->code,
                $queue['date']->format('Ymd'),
                $queue['number']
            ),
            'queue_number' => $queue['number'],
            'queue_date' => $queue['date'],
            'queue_code' => sprintf(
                '%s%03d',
                $canteen->code,
                $queue['number']
            ),
            'total' => $total,
            'status' => Order::STATUS_PENDING,

            /*
             * Saldo = sudah dibayar.
             * Outlet = belum dibayar.
             */
            'payment_status' => $paymentMethod === Order::PAYMENT_BALANCE
                ? 'paid'
                : 'unpaid',

            'payment_method' => $paymentMethod,
            'ordered_at' => now(),
        ]);

        $order->items()->createMany($rows);

        Cart::clear();

        return $order;
    }, 3);
}

    /**
     * Membatalkan pesanan dan mengembalikan stok.
     *
     * Fitur ini tetap dipertahankan di service karena dapat digunakan
     * oleh proses internal/admin/penjual jika diperlukan.
     */
    public function cancel(Order $order, ?string $reason = null): void
    {
        DB::transaction(function () use ($order, $reason) {
            $order = Order::lockForUpdate()->findOrFail($order->id);
            $student = Student::lockForUpdate()->findOrFail($order->student_id);

            if (! $order->isActive()) {
                throw ValidationException::withMessages([
                    'status' => 'Pesanan ini sudah tidak bisa dibatalkan.',
                ]);
            }

            if (
                $order->payment_method === Order::PAYMENT_BALANCE &&
                $order->payment_status === 'paid'
            ) {
                $student->increment('balance', $order->total);
            }

            foreach ($order->items as $item) {
                if ($item->product_id) {
                    Product::where('id', $item->product_id)
                        ->increment('stock', $item->quantity);
                }
            }

            $order->update([
                'status' => Order::STATUS_CANCELLED,
                'cancel_reason' => $reason,
            ]);
        });
    }

    /**
     * Nomor antrean urut per kantin, di-reset setiap hari.
     *
     * Dipanggil di dalam transaction yang sudah mengunci baris kantin,
     * sehingga tidak ada dua pesanan yang mendapat nomor sama.
     *
     * @return array{number: int, date: \Illuminate\Support\Carbon}
     */
    private function nextQueueNumber(Canteen $canteen): array
    {
        $date = now()->startOfDay();

        $last = Order::where('canteen_id', $canteen->id)
            ->whereDate('queue_date', $date)
            ->max('queue_number');

        return [
            'number' => ((int) $last) + 1,
            'date' => $date,
        ];
    }
}