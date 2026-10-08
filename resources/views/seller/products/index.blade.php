@extends('layouts.app')
@section('title', 'Kelola menu')

@section('content')
<div class="mb-4 flex flex-wrap items-center justify-between gap-3">
    <h1 class="text-xl font-bold">Kelola menu</h1>
    <a href="{{ route('seller.products.create') }}" class="btn-primary">Tambah menu</a>
</div>

<form method="GET" class="mb-4 flex gap-2">
    <input name="q" value="{{ request('q') }}" class="input sm:max-w-xs" placeholder="Cari menu">
    <button type="submit" class="btn-secondary">Cari</button>
</form>

<div class="grid gap-3 sm:grid-cols-2">
    @forelse ($products as $product)
        <div class="card p-4">
            <div class="flex gap-3">
                <div class="h-20 w-20 shrink-0 overflow-hidden rounded-xl">
                    @include('components.product-image', ['image' => $product->image, 'name' => $product->name])
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate font-semibold">{{ $product->name }}</p>
                    <p class="text-sm text-gray-500">{{ $product->category?->name ?? 'Tanpa kategori' }}</p>
                    <p class="mt-1 font-bold text-brand-600">{{ rupiah($product->price) }}</p>
                    <span class="badge mt-1 {{ $product->is_available ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $product->is_available ? 'Tersedia' : 'Tidak tersedia' }}
                    </span>
                </div>
            </div>

            <div class="mt-3 flex flex-wrap items-end gap-2 border-t border-gray-100 pt-3">
                <form method="POST" action="{{ route('seller.products.stock', $product) }}" class="flex items-end gap-2">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="label text-xs" for="stock-{{ $product->id }}">Stok</label>
                        <input id="stock-{{ $product->id }}" name="stock" type="number" min="0" value="{{ $product->stock }}"
                               class="input w-24 px-2 py-1.5 text-sm">
                    </div>
                    <button type="submit" class="btn-secondary px-3 py-1.5 text-xs">Simpan</button>
                </form>

                <form method="POST" action="{{ route('seller.products.availability', $product) }}">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn-secondary px-3 py-1.5 text-xs">
                        {{ $product->is_available ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                </form>

                <a href="{{ route('seller.products.edit', $product) }}" class="btn-secondary px-3 py-1.5 text-xs">Edit</a>

                <form method="POST" action="{{ route('seller.products.destroy', $product) }}"
                      data-confirm="Hapus menu {{ $product->name }}? Riwayat transaksi lama tetap tersimpan.">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger px-3 py-1.5 text-xs">Hapus</button>
                </form>
            </div>
        </div>
    @empty
        <div class="sm:col-span-2">
            @include('components.empty', [
                'title' => 'Belum ada menu',
                'message' => 'Tambahkan menu pertama supaya siswa bisa mulai memesan dari kantinmu.',
                'action' => route('seller.products.create'),
                'actionLabel' => 'Tambah menu',
            ])
        </div>
    @endforelse
</div>

<div class="mt-4">{{ $products->links() }}</div>
@endsection
