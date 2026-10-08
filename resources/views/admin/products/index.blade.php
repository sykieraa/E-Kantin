@extends('layouts.app')
@section('title', 'Semua menu')

@section('content')
<h1 class="mb-1 text-xl font-bold">Semua menu</h1>
<p class="mb-4 text-sm text-gray-500">Halaman pemantauan. Penambahan dan perubahan menu dilakukan oleh masing-masing penjual.</p>

<form method="GET" class="mb-4 flex flex-wrap gap-2">
    <input name="q" value="{{ request('q') }}" class="input sm:max-w-xs" placeholder="Cari menu">
    <select name="canteen" class="input sm:max-w-[200px]">
        <option value="">Semua kantin</option>
        @foreach ($canteens as $canteen)
            <option value="{{ $canteen->id }}" @selected(request('canteen') == $canteen->id)>{{ $canteen->name }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn-secondary">Terapkan</button>
</form>

<div class="table-wrap">
    <table class="min-w-full divide-y divide-gray-200">
        <thead><tr><th class="th">Menu</th><th class="th">Kantin</th><th class="th">Kategori</th><th class="th">Harga</th><th class="th">Stok</th><th class="th">Status</th></tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($products as $product)
                <tr>
                    <td class="td">{{ $product->name }}</td>
                    <td class="td">{{ $product->canteen->name }}</td>
                    <td class="td">{{ $product->category?->name ?? '—' }}</td>
                    <td class="td">{{ rupiah($product->price) }}</td>
                    <td class="td {{ $product->stock === 0 ? 'font-semibold text-red-600' : '' }}">{{ $product->stock }}</td>
                    <td class="td">
                        <span class="badge {{ $product->isOrderable() ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $product->isOrderable() ? 'Bisa dipesan' : 'Tidak bisa dipesan' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr><td class="td text-gray-500" colspan="6">Belum ada menu.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $products->links() }}</div>
@endsection
