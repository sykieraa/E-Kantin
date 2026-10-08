@extends('layouts.app')
@section('title', 'Keranjang')

@section('content')
<div class="space-y-5 pb-6">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-3">
        <h1 class="text-xl font-extrabold tracking-tight text-gray-900">
            Keranjang Belanja
        </h1>

        @if (count($lines) > 0)
            <form
                method="POST"
                action="{{ route('student.cart.clear') }}"
                data-confirm="Kosongkan seluruh isi keranjang?"
            >
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="text-xs font-semibold text-red-600 hover:underline"
                >
                    Kosongkan Keranjang
                </button>
            </form>
        @endif
    </div>

    {{-- Keranjang kosong --}}
    @if (count($lines) === 0)

        @include('components.empty', [
            'title' => 'Keranjang masih kosong',
            'message' => 'Pilih kantin yang sedang buka, lalu tambahkan menu favoritmu.',
            'action' => route('student.dashboard'),
            'actionLabel' => 'Lihat daftar kantin',
        ])

    @else

        {{-- Informasi kantin --}}
        @if ($canteen)
            <div class="card flex items-center justify-between gap-3 border-brand-100 bg-brand-50/50 p-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-500 font-bold text-white shadow-sm shadow-brand-500/30">
                        {{ $canteen->code }}
                    </span>

                    <div>
                        <p class="text-xs font-medium text-gray-500">
                            Memesan dari kantin
                        </p>

                        <p class="font-bold text-gray-900">
                            {{ $canteen->name }}
                        </p>
                    </div>
                </div>

                @include('components.canteen-status', [
                    'status' => $canteen->status
                ])
            </div>
        @endif

        {{-- Masalah pada keranjang --}}
        @foreach ($issues as $issue)
            <div class="flex items-center gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-xs font-medium text-amber-800">
                <svg
                    class="h-5 w-5 shrink-0 text-amber-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                    />
                </svg>

                <span>{{ $issue }}</span>
            </div>
        @endforeach

        {{-- Daftar menu --}}
        <div class="space-y-3">

            @foreach ($lines as $line)
                @php
                    $product = $line['product'];
                @endphp

                <div class="card flex flex-col gap-4 p-4 shadow-sm transition hover:shadow-md sm:flex-row sm:items-center sm:justify-between">

                    {{-- Produk --}}
                    <div class="flex min-w-0 items-center gap-3.5">

                        <div class="h-16 w-16 shrink-0 overflow-hidden rounded-2xl bg-gray-100">
                            @include('components.product-image', [
                                'image' => $product->image,
                                'name' => $product->name
                            ])
                        </div>

                        <div class="min-w-0">
                            <p class="truncate font-bold text-gray-900">
                                {{ $product->name }}
                            </p>

                            <p class="text-sm text-gray-500">
                                {{ rupiah($product->price) }} / porsi
                            </p>

                            <p class="mt-1 text-xs font-bold text-brand-600 sm:hidden">
                                Subtotal: {{ rupiah($line['subtotal']) }}
                            </p>
                        </div>

                    </div>

                    {{-- Kontrol jumlah + subtotal + hapus --}}
                    <div class="flex items-center justify-between gap-4 border-t border-gray-100 pt-3 sm:border-0 sm:pt-0">

                        {{-- Stepper jumlah --}}
                        <div class="flex items-center rounded-xl border border-gray-200 bg-gray-50/80 p-1">

                            {{-- Kurangi / hapus jika jumlah 1 --}}
                            @if ($line['quantity'] > 1)

                                <form
                                    method="POST"
                                    action="{{ route('student.cart.update', $product) }}"
                                >
                                    @csrf
                                    @method('PUT')

                                    <input
                                        type="hidden"
                                        name="quantity"
                                        value="{{ $line['quantity'] - 1 }}"
                                    >

                                    <button
                                        type="submit"
                                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-white font-bold text-gray-700 shadow-sm transition hover:bg-gray-100"
                                        aria-label="Kurangi jumlah"
                                    >
                                        &minus;
                                    </button>
                                </form>

                            @else

                                <form
                                    method="POST"
                                    action="{{ route('student.cart.destroy', $product) }}"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-red-600 shadow-sm transition hover:bg-red-50"
                                        aria-label="Hapus item"
                                    >
                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                            />
                                        </svg>
                                    </button>
                                </form>

                            @endif

                            {{-- Jumlah --}}
                            <span class="w-10 text-center text-sm font-extrabold text-gray-900">
                                {{ $line['quantity'] }}
                            </span>

                            {{-- Tambah --}}
                            <form
                                method="POST"
                                action="{{ route('student.cart.update', $product) }}"
                            >
                                @csrf
                                @method('PUT')

                                <input
                                    type="hidden"
                                    name="quantity"
                                    value="{{ $line['quantity'] + 1 }}"
                                >

                                <button
                                    type="submit"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-white font-bold text-gray-700 shadow-sm transition hover:bg-gray-100 disabled:opacity-40"
                                    @disabled($line['quantity'] >= $product->stock)
                                    aria-label="Tambah jumlah"
                                >
                                    +
                                </button>
                            </form>

                        </div>

                        {{-- Subtotal --}}
                        <div class="hidden text-right sm:block">
                            <p class="text-[11px] font-medium text-gray-400">
                                Subtotal
                            </p>

                            <p class="text-sm font-black text-brand-600">
                                {{ rupiah($line['subtotal']) }}
                            </p>
                        </div>

                        {{-- Hapus --}}
                        <form
                            method="POST"
                            action="{{ route('student.cart.destroy', $product) }}"
                            class="hidden sm:block"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="rounded-lg p-2 text-gray-400 transition hover:bg-red-50 hover:text-red-600"
                                title="Hapus menu ini"
                            >
                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                    />
                                </svg>
                            </button>
                        </form>

                    </div>

                </div>
            @endforeach

        </div>

        {{-- Ringkasan --}}
        <div class="card p-5 shadow-md">

            <div class="space-y-2 border-b border-gray-100 pb-4">

                <div class="flex justify-between text-xs text-gray-500">
                    <span>Jumlah Menu</span>

                    <span class="font-semibold text-gray-800">
                        {{ count($lines) }} jenis
                    </span>
                </div>

                <div class="flex justify-between text-xs text-gray-500">
                    <span>Total Porsi</span>

                    <span class="font-semibold text-gray-800">
                        {{ array_sum(array_column($lines, 'quantity')) }} porsi
                    </span>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <span class="text-sm font-semibold text-gray-700">
                        Total Pembayaran
                    </span>

                    <span class="text-2xl font-black text-brand-600">
                        {{ rupiah($total) }}
                    </span>
                </div>

            </div>

            {{-- Checkout --}}
            <div class="mt-4">

                @if ($canteen && $canteen->isAcceptingOrders() && count($issues) === 0)

                    <a
                        href="{{ route('student.checkout.index') }}"
                        class="btn-primary block w-full rounded-xl px-6 py-3 text-center text-sm font-bold shadow-md shadow-brand-500/25"
                    >
                        Lanjut ke Checkout &rarr;
                    </a>

                @else

                    <button
                        type="button"
                        class="btn-primary block w-full rounded-xl px-6 py-3 text-sm font-bold opacity-50"
                        disabled
                    >
                        Lanjut ke Checkout
                    </button>

                @endif

                @if ($canteen && ! $canteen->isAcceptingOrders())
                    <p class="mt-3 rounded-xl bg-amber-50 p-3 text-xs font-medium text-amber-800">
                        {{ $canteen->closedMessage() }}
                    </p>
                @endif

            </div>

        </div>

    @endif

</div>
@endsection