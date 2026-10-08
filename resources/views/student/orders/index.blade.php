@extends('layouts.app')
@section('title', 'Riwayat pesanan')

@section('content')
<h1 class="mb-4 text-xl font-bold">Riwayat pesanan</h1>

@forelse ($orders as $order)
    <a href="{{ route('student.orders.show', $order) }}" class="card mb-3 block p-4 hover:border-brand-300">
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
                <p class="text-lg font-extrabold text-brand-600">{{ $order->queue_code }}</p>
                <p class="truncate text-sm text-gray-600">
                    {{ $order->items->pluck('product_name')->take(2)->implode(', ') }}
                    @if ($order->items->count() > 2)
                        <span class="text-gray-400">+{{ $order->items->count() - 2 }} menu lain</span>
                    @endif
                </p>
                <p class="mt-1 text-xs text-gray-400">{{ $order->canteen->name }} &middot; {{ $order->ordered_at->translatedFormat('d F Y, H:i') }}</p>
            </div>
            <div class="shrink-0 text-right">
                <p class="font-bold">{{ rupiah($order->total) }}</p>
                <div class="mt-1">@include('components.order-status', ['status' => $order->status])</div>
            </div>
        </div>
    </a>
@empty
    @include('components.empty', [
        'title' => 'Belum ada pesanan',
        'message' => 'Pesanan yang kamu buat akan tersimpan di sini, lengkap dengan nomor antrean dan rinciannya.',
        'action' => route('student.dashboard'),
        'actionLabel' => 'Mulai pesan',
    ])
@endforelse

<div class="mt-4">{{ $orders->links() }}</div>
@endsection
