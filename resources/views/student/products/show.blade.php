@extends('layouts.app')
@section('title', $product->name)

@section('content')
<a href="{{ route('student.canteens.show', $product->canteen) }}" class="text-sm text-gray-500 hover:underline">&larr; {{ $product->canteen->name }}</a>

<div class="card mt-3 overflow-hidden">
    <div class="aspect-[16/9] w-full sm:aspect-[21/9]">
        @include('components.product-image', ['image' => $product->image, 'name' => $product->name])
    </div>

    <div class="p-5">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold">{{ $product->name }}</h1>
                <p class="text-sm text-gray-500">{{ $product->category?->name ?? 'Tanpa kategori' }}</p>
            </div>
            <p class="text-2xl font-extrabold text-brand-600">{{ rupiah($product->price) }}</p>
        </div>

        <p class="mt-4 text-sm leading-relaxed text-gray-600">{{ $product->description ?: 'Penjual belum menambahkan deskripsi untuk menu ini.' }}</p>

        <div class="mt-4 flex flex-wrap items-center gap-2">
            @include('components.canteen-status', ['status' => $product->canteen->status])
            @if (! $product->is_available)
                <span class="badge bg-gray-100 text-gray-600">Tidak tersedia</span>
            @elseif ($product->stock === 0)
                <span class="badge bg-red-50 text-red-700">Stok habis</span>
            @else
                <span class="badge bg-gray-100 text-gray-700">Stok tersisa {{ $product->stock }}</span>
            @endif
        </div>

        @if ($product->isOrderable() && $product->canteen->isAcceptingOrders())
            <form method="POST" action="{{ route('student.cart.store', $product) }}" class="mt-6 flex flex-wrap items-end gap-3">
                @csrf
                <div class="w-28">
                    <label for="quantity" class="label">Jumlah</label>
                    <input id="quantity" name="quantity" type="number" value="1" min="1" max="{{ $product->stock }}" class="input">
                </div>
                <button type="submit" class="btn-primary">Tambahkan ke keranjang</button>
            </form>
            <p class="mt-2 text-xs text-gray-500">Jumlah maksimal mengikuti stok yang tersedia.</p>
        @else
            <p class="mt-6 rounded-xl bg-gray-50 px-4 py-3 text-sm text-gray-600">
                {{ $product->canteen->closedMessage() ?? 'Menu ini sedang tidak bisa dipesan.' }}
            </p>
        @endif
    </div>
</div>
@endsection
