@extends('layouts.app')
@section('title', 'Semua pesanan')

@section('content')
<h1 class="mb-4 text-xl font-bold">Semua pesanan</h1>

<form method="GET" class="mb-4 flex flex-wrap gap-2">
    <select name="canteen" class="input sm:max-w-[180px]">
        <option value="">Semua kantin</option>
        @foreach ($canteens as $canteen)
            <option value="{{ $canteen->id }}" @selected(request('canteen') == $canteen->id)>{{ $canteen->name }}</option>
        @endforeach
    </select>
    <select name="status" class="input sm:max-w-[180px]">
        <option value="">Semua status</option>
        @foreach (\App\Models\Order::statusOptions() as $value => $label)
            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
        @endforeach
    </select>
    <input type="date" name="date" value="{{ request('date') }}" class="input sm:max-w-[180px]">
    <button type="submit" class="btn-secondary">Terapkan</button>
    <a href="{{ route('admin.orders.index') }}" class="btn-secondary">Reset</a>
</form>

<div class="table-wrap">
    <table class="min-w-full divide-y divide-gray-200">
        <thead><tr><th class="th">Antrean</th><th class="th">Nomor pesanan</th><th class="th">Siswa</th><th class="th">Kantin</th><th class="th">Waktu</th><th class="th">Total</th><th class="th">Status</th></tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($orders as $order)
                <tr>
                    <td class="td font-bold text-brand-600">
                        <a href="{{ route('admin.orders.show', $order) }}" class="hover:underline">{{ $order->queue_code }}</a>
                    </td>
                    <td class="td text-gray-500">{{ $order->order_number }}</td>
                    <td class="td">{{ $order->student->name }}</td>
                    <td class="td">{{ $order->canteen->name }}</td>
                    <td class="td">{{ $order->ordered_at->translatedFormat('d M Y, H:i') }}</td>
                    <td class="td font-semibold">{{ rupiah($order->total) }}</td>
                    <td class="td">@include('components.order-status', ['status' => $order->status])</td>
                </tr>
            @empty
                <tr><td class="td text-gray-500" colspan="7">Tidak ada pesanan yang cocok dengan filter ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $orders->links() }}</div>
@endsection
