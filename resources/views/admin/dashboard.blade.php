@extends('layouts.app')
@section('title', 'Dashboard admin')

@section('content')
<h1 class="mb-1 text-xl font-bold">Dashboard</h1>
<p class="mb-5 text-sm text-gray-500">Kondisi seluruh kantin sekolah, {{ today()->translatedFormat('d F Y') }}</p>

@php
    $cards = [
        ['Total siswa', $stats['students']],
        ['Total kantin', $stats['canteens']],
        ['Total menu', $stats['products']],
        ['Pesanan hari ini', $stats['orders_today']],
        ['Kantin buka', $stats['open']],
        ['Kantin tutup', $stats['closed']],
    ];
@endphp

<div class="grid grid-cols-2 gap-3 lg:grid-cols-3">
    @foreach ($cards as [$label, $value])
        <div class="card p-4">
            <p class="text-sm text-gray-500">{{ $label }}</p>
            <p class="mt-1 text-3xl font-extrabold">{{ $value }}</p>
        </div>
    @endforeach
</div>

<div class="card mt-3 p-4">
    <p class="text-sm text-gray-500">Transaksi selesai hari ini</p>
    <p class="mt-1 text-3xl font-extrabold text-brand-600">{{ rupiah($stats['revenue_today']) }}</p>
</div>

<section class="mt-6">
    <h2 class="mb-2 text-sm font-semibold text-gray-700">Kantin</h2>
    <div class="table-wrap">
        <table class="min-w-full divide-y divide-gray-200">
            <thead><tr><th class="th">Kode</th><th class="th">Kantin</th><th class="th">Penjual</th><th class="th">Menu</th><th class="th">Status</th></tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($canteens as $canteen)
                    <tr>
                        <td class="td font-bold">{{ $canteen->code }}</td>
                        <td class="td">{{ $canteen->name }}</td>
                        <td class="td">{{ $canteen->seller->name }}</td>
                        <td class="td">{{ $canteen->products_count }}</td>
                        <td class="td">@include('components.canteen-status', ['status' => $canteen->status])</td>
                    </tr>
                @empty
                    <tr><td class="td text-gray-500" colspan="5">Belum ada kantin terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
