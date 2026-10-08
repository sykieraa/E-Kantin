@extends('layouts.app')
@section('title', 'Riwayat transaksi')

@section('content')
<h1 class="mb-4 text-xl font-bold">Riwayat transaksi</h1>

<form method="GET" class="card mb-4 flex flex-wrap items-end gap-3 p-4">
    <div>
        <label for="month" class="label">Bulan transaksi</label>
        <input id="month" type="month" name="month" value="{{ request('month', now()->format('Y-m')) }}" class="input">
    </div>
    <button type="submit" class="btn-primary">Terapkan</button>
    <a href="{{ route('seller.history.index') }}" class="btn-secondary">Reset</a>
</form>

<div class="card mb-4 p-4">
    <p class="text-sm text-gray-500">Total pemasukan dari pesanan selesai pada bulan yang dipilih</p>
    <p class="mt-1 text-3xl font-extrabold">{{ rupiah($revenue) }}</p>
</div>

<div class="table-wrap">
    <table class="min-w-full divide-y divide-gray-200">
        <thead>
            <tr><th class="th">Antrean</th><th class="th">Nomor pesanan</th><th class="th">Siswa</th><th class="th">Tanggal</th><th class="th">Total</th><th class="th">Status</th></tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($orders as $order)
                <tr>
                    <td class="td font-bold text-brand-600">
                        <a href="{{ route('seller.orders.show', $order) }}" class="hover:underline">{{ $order->queue_code }}</a>
                    </td>
                    <td class="td text-gray-500">{{ $order->order_number }}</td>
                    <td class="td">{{ $order->student->name }}</td>
                    <td class="td">{{ $order->ordered_at->translatedFormat('d M Y, H:i') }}</td>
                    <td class="td font-semibold">{{ rupiah($order->total) }}</td>
                    <td class="td">@include('components.order-status', ['status' => $order->status])</td>
                </tr>
            @empty
                <tr><td class="td text-gray-500" colspan="6">Belum ada transaksi pada bulan ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $orders->links() }}</div>
@endsection
