@extends('layouts.app')
@section('title', 'Pesanan '.$order->queue_code)

@section('content')
<a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-500 hover:underline">&larr; Semua pesanan</a>

<div class="card mt-3 p-5">
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
            <p class="text-3xl font-extrabold text-brand-600">{{ $order->queue_code }}</p>
            <p class="mt-1 text-sm text-gray-500">{{ $order->order_number }}</p>
        </div>
        @include('components.order-status', ['status' => $order->status])
    </div>

    <dl class="mt-5 grid gap-2 border-t border-gray-100 pt-4 text-sm sm:grid-cols-2">
        <div class="flex justify-between gap-4"><dt class="text-gray-500">Siswa</dt><dd class="font-medium">{{ $order->student->name }}</dd></div>
        <div class="flex justify-between gap-4"><dt class="text-gray-500">Kelas</dt><dd class="font-medium">{{ $order->student->class_name }}</dd></div>
        <div class="flex justify-between gap-4"><dt class="text-gray-500">Kantin</dt><dd class="font-medium">{{ $order->canteen->name }}</dd></div>
        <div class="flex justify-between gap-4"><dt class="text-gray-500">Waktu</dt><dd class="font-medium">{{ $order->ordered_at->translatedFormat('d F Y, H:i') }}</dd></div>
        <div class="flex justify-between gap-4"><dt class="text-gray-500">Pembayaran</dt><dd class="font-medium">{{ $order->payment_status === 'paid' ? 'Lunas' : 'Belum dibayar' }}</dd></div>
    </dl>

    <ul class="mt-4 divide-y divide-gray-100 border-t border-gray-100 pt-2">
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

    <div class="flex items-center justify-between border-t border-gray-100 pt-4">
        <p class="text-sm text-gray-600">Total</p>
        <p class="text-2xl font-extrabold text-brand-600">{{ rupiah($order->total) }}</p>
    </div>
</div>
@endsection
