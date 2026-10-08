@extends('layouts.app')
@section('title', $canteen->name)

@section('content')
<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <div>
        <a href="{{ route('student.dashboard') }}" class="text-sm text-gray-500 hover:underline">&larr; Semua kantin</a>
        <h1 class="mt-1 text-xl font-bold">{{ $canteen->name }}</h1>
        <p class="text-sm text-gray-500">{{ $canteen->description }}</p>
    </div>
    @include('components.canteen-status', ['status' => $canteen->status])
</div>

@unless ($canteen->isAcceptingOrders())
    <div class="mb-5 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
        {{ $canteen->closedMessage() }} Kamu masih bisa melihat menu, tapi belum bisa memesan.
    </div>
@endunless

<form method="GET" class="mb-5 flex flex-wrap gap-2">
    <input name="q" value="{{ $search }}" class="input sm:max-w-xs" placeholder="Cari menu">
    <select name="category" class="input sm:max-w-[180px]">
        <option value="">Semua kategori</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected($categoryId == $category->id)>{{ $category->name }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn-secondary">Terapkan</button>
</form>

<div class="grid gap-3 sm:grid-cols-2">
    @forelse ($products as $product)
        <div class="card flex gap-4 p-3">
            <a href="{{ route('student.products.show', $product) }}" class="h-24 w-24 shrink-0 overflow-hidden rounded-xl">
                @include('components.product-image', ['image' => $product->image, 'name' => $product->name])
            </a>

            <div class="flex min-w-0 flex-1 flex-col justify-between">
                <div>
                    <a href="{{ route('student.products.show', $product) }}" class="block truncate font-semibold hover:text-brand-600">{{ $product->name }}</a>
                    <p class="text-sm text-gray-500">{{ $product->category?->name ?? 'Tanpa kategori' }}</p>
                    <p class="mt-1 font-bold text-brand-600">{{ rupiah($product->price) }}</p>
                </div>

                <div class="mt-2 flex items-center justify-between gap-2">
                    @if (! $product->is_available)
                        <span class="badge bg-gray-100 text-gray-600">Tidak tersedia</span>
                    @elseif ($product->stock === 0)
                        <span class="badge bg-red-50 text-red-700">Stok habis</span>
                    @else
                        <span class="text-xs text-gray-500">Stok: {{ $product->stock }}</span>
                    @endif

                    <form method="POST" action="{{ route('student.cart.store', $product) }}" class="flex items-center gap-2">
                        @csrf
                        <label class="sr-only" for="quantity-{{ $product->id }}">Jumlah {{ $product->name }}</label>
                        <input id="quantity-{{ $product->id }}" name="quantity" type="number" value="1" min="1" max="{{ $product->stock }}"
                               class="input w-16 px-2 py-1.5 text-center text-xs">
                        <button type="submit" class="btn-primary px-3 py-2 text-xs"
                                @disabled(! $product->isOrderable() || ! $canteen->isAcceptingOrders())>
                            Tambah
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="sm:col-span-2">
            @include('components.empty', [
                'title' => 'Belum ada menu',
                'message' => 'Kantin ini belum mengisi menu, atau tidak ada menu yang cocok dengan pencarianmu.',
            ])
        </div>
    @endforelse
</div>

<div class="mt-5">{{ $products->links() }}</div>
@endsection
