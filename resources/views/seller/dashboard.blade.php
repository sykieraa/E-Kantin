@extends('layouts.app')
@section('title', 'Dashboard penjual')

@section('content')
<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class="text-xl font-bold">{{ $canteen->name }}</h1>
        <p class="text-sm text-gray-500">Ringkasan hari ini, {{ today()->translatedFormat('d F Y') }}</p>
    </div>
    <div class="flex items-center gap-2">
        @include('components.canteen-status', ['status' => $canteen->status])
    </div>
</div>

@php
    $cards = [
        ['Pesanan baru', $counts['pending'] ?? 0, 'text-amber-600'],
        ['Diproses', $counts['processing'] ?? 0, 'text-blue-600'],
        ['Siap diambil', $counts['ready'] ?? 0, 'text-brand-600'],
        ['Selesai', $counts['completed'] ?? 0, 'text-emerald-600'],
    ];
@endphp

<div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
    @foreach ($cards as [$label, $value, $color])
        <div class="card p-4">
            <p class="text-sm text-gray-500">{{ $label }}</p>
            <p class="mt-1 text-3xl font-extrabold {{ $color }}">{{ $value }}</p>
        </div>
    @endforeach
</div>

<div class="card mt-3 p-4">
    <p class="text-sm text-gray-500">Penjualan hari ini (pesanan selesai)</p>
    <p class="mt-1 text-3xl font-extrabold">{{ rupiah($revenueToday) }}</p>
</div>

<div class="mt-6 grid gap-4 lg:grid-cols-2">
    <section>
        <h2 class="mb-2 text-sm font-semibold text-gray-700">Pesanan terbaru</h2>
        <div class="table-wrap">
            <table class="min-w-full divide-y divide-gray-200">
                <thead><tr><th class="th">Antrean</th><th class="th">Siswa</th><th class="th">Total</th><th class="th">Status</th></tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($latestOrders as $order)
                        <tr>
                            <td class="td font-bold text-brand-600">
                                <a href="{{ route('seller.orders.show', $order) }}" class="hover:underline">{{ $order->queue_code }}</a>
                            </td>
                            <td class="td">{{ $order->student->name }}</td>
                            <td class="td">{{ rupiah($order->total) }}</td>
                            <td class="td">@include('components.order-status', ['status' => $order->status])</td>
                        </tr>
                    @empty
                        <tr><td class="td text-gray-500" colspan="4">Belum ada pesanan masuk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section>
        <h2 class="mb-2 text-sm font-semibold text-gray-700">Stok menipis (5 atau kurang)</h2>
        <div class="table-wrap">
            <table class="min-w-full divide-y divide-gray-200">
                <thead><tr><th class="th">Menu</th><th class="th">Sisa stok</th><th class="th"></th></tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($lowStock as $product)
                        <tr>
                            <td class="td">{{ $product->name }}</td>
                            <td class="td {{ $product->stock === 0 ? 'font-bold text-red-600' : 'font-semibold text-amber-600' }}">{{ $product->stock }}</td>
                            <td class="td"><a href="{{ route('seller.products.edit', $product) }}" class="text-brand-600 hover:underline">Isi ulang</a></td>
                        </tr>
                    @empty
                        <tr><td class="td text-gray-500" colspan="3">Semua stok masih aman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
