@extends('layouts.app')
@section('title', 'Pesanan masuk')

@section('content')
<h1 class="mb-1 text-xl font-bold">Pesanan masuk</h1>
<p class="mb-4 text-sm text-gray-500">Pesanan yang sudah selesai atau dibatalkan pindah ke halaman Riwayat.</p>

<div class="mb-4 flex flex-wrap gap-2">
    @foreach (['' => 'Semua', 'pending' => 'Menunggu', 'processing' => 'Diproses', 'ready' => 'Siap diambil'] as $value => $label)
        <a href="{{ route('seller.orders.index', array_filter(['status' => $value])) }}"
           class="rounded-full px-3 py-1.5 text-sm font-medium {{ $status === ($value ?: null) ? 'bg-brand-600 text-white' : 'border border-gray-300 bg-white text-gray-600' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

<div class="space-y-3">
    @forelse ($orders as $order)
        <div class="card p-4">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-2xl font-extrabold text-brand-600">{{ $order->queue_code }}</p>
                    <p class="text-sm text-gray-600">{{ $order->student->name }} &middot; {{ $order->student->class_name }}</p>
                    <p class="text-xs text-gray-400">{{ $order->ordered_at->translatedFormat('d M Y, H:i') }}</p>
                </div>
                <div class="text-right">
                    <p class="font-bold">{{ rupiah($order->total) }}</p>
                    <div class="mt-1">@include('components.order-status', ['status' => $order->status])</div>
                </div>
            </div>

            <ul class="mt-3 border-t border-gray-100 pt-3 text-sm text-gray-600">
                @foreach ($order->items as $item)
                    <li>{{ $item->product_name }} <span class="text-gray-400">&times; {{ $item->quantity }}</span></li>
                @endforeach
            </ul>

            @include('seller.orders.actions', ['order' => $order])
        </div>
    @empty
        @include('components.empty', [
            'title' => 'Belum ada pesanan aktif',
            'message' => 'Pesanan baru dari siswa akan muncul di sini.',
        ])
    @endforelse
</div>

<div class="mt-4">{{ $orders->links() }}</div>
@endsection
