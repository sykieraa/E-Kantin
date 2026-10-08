@extends('layouts.app')
@section('title', 'Pesanan '.$order->queue_code)

@section('content')
<a href="{{ route('student.orders.index') }}" class="text-sm text-gray-500 hover:underline">&larr; Riwayat pesanan</a>

{{-- Kartu antrean. Bagian ini sengaja dibuat paling menonjol di halaman. --}}
<div class="card mt-3 overflow-hidden"
     @if ($order->isActive())
        data-order-status-url="{{ route('student.orders.status', $order) }}"
        data-order-status="{{ $order->status }}"
     @endif>
    <div class="bg-brand-600 px-6 py-8 text-center text-white">
        <p class="text-xs text-brand-100">Pesanan {{ $order->order_number }}</p>
        <p class="my-2 text-6xl font-extrabold tracking-tight">{{ $order->queue_code }}</p>
        <p class="text-sm text-brand-100">{{ $order->canteen->name }}</p>
    </div>

    <div class="px-6 py-5 text-center">
        @include('components.order-status', ['status' => $order->status])

        @if ($order->status === 'ready')
            <p class="mt-3 text-base font-bold text-brand-700">Pesanan {{ $order->queue_code }} sudah siap diambil!</p>
            <p class="text-sm text-gray-500">Tunjukkan nomor ini ke penjual.</p>
        @elseif ($order->status === 'pending')
            <p class="mt-3 text-sm text-gray-600">Ada {{ $ahead }} pesanan lain di depan kamu.</p>
        @elseif ($order->status === 'processing')
            <p class="mt-3 text-sm text-gray-600">Pesanan kamu sedang disiapkan penjual.</p>
        @elseif ($order->status === 'cancelled')
            <p class="mt-3 text-sm text-gray-600">{{ $order->cancel_reason ?? 'Pesanan dibatalkan.' }}</p>
        @else
            <p class="mt-3 text-sm text-gray-600">Terima kasih, pesanan sudah selesai.</p>
        @endif

        @if ($order->isActive())
            <p class="mt-4 text-xs text-gray-400">Status diperbarui otomatis setiap beberapa detik.</p>
        @endif
    </div>
</div>

<div class="card mt-4 p-5">
    <h2 class="mb-3 text-sm font-semibold text-gray-700">Rincian pesanan</h2>

    <ul class="divide-y divide-gray-100">
        @foreach ($order->items as $item)
            <li class="flex items-center justify-between gap-4 py-3 text-sm">
                <div>
                    <p class="font-medium">{{ $item->product_name }}</p>
                    <p class="text-gray-500">{{ rupiah($item->price) }} &times; {{ $item->quantity }}</p>
                </div>
                <p class="font-semibold">{{ rupiah($item->subtotal) }}</p>
            </li>
        @endforeach
    </ul>

    <dl class="mt-4 space-y-2 border-t border-gray-100 pt-4 text-sm">
        <div class="flex justify-between"><dt class="text-gray-500">Waktu pemesanan</dt><dd>{{ $order->ordered_at->translatedFormat('d F Y, H:i') }}</dd></div>
        <div class="flex justify-between"><dt class="text-gray-500">Pembayaran</dt><dd>{{ $order->payment_status === 'paid' ? 'Lunas' : 'Bayar saat mengambil' }}</dd></div>
        <div class="flex justify-between"><dt class="text-gray-500">Pembayaran</dt><dd>{{ $order->paymentMethodLabel() }}{{ $order->payment_status === 'paid' ? ' (lunas)' : '' }}</dd></div>
        <div class="flex justify-between text-base font-bold"><dt>Total</dt><dd class="text-brand-600">{{ rupiah($order->total) }}</dd></div>
    </dl>
</div>
@endsection
