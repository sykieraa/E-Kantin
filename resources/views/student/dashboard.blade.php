@extends('layouts.app')
@section('title', 'Kantin')

@section('content')

<div class="space-y-6 pb-6">

    {{-- 1. Greeting & User Context --}}
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-500 text-lg font-black text-white shadow-sm shadow-brand-500/30">
                @include('components.icon.user', ['class' => 'h-6 w-6'])
            </div>

            <div>
                <p class="text-xs font-medium text-gray-500">Selamat Datang 👋</p>
                <h1 class="text-lg font-extrabold tracking-tight text-gray-900">
                    {{ $student?->name ?? auth()->user()->name }}
                </h1>
                <p class="text-xs text-gray-400">
                    {{ $student?->class_name }} &bull; NIS: {{ $student?->nis }}
                </p>
            </div>
        </div>
    </div>

    {{-- 2. Saldo Siswa & CTA Top Up --}}
    <div class="card relative overflow-hidden bg-gradient-to-r from-brand-500 to-brand-600 p-5 text-white shadow-md shadow-brand-500/20">
        <div class="relative z-10 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/20 text-white backdrop-blur-md">
                    <x-icon.wallet class="h-6 w-6" />
                </div>

                <div>
                    <p class="text-xs font-medium text-brand-100">Saldo E-Kantin</p>
                    <p class="text-2xl font-black tracking-tight text-white">
                        {{ rupiah($student?->balance ?? 0) }}
                    </p>
                </div>
            </div>

            <button
                type="button"
                data-toggle="#topup-modal-dash"
                class="inline-flex items-center gap-1.5 rounded-xl bg-white px-4 py-2 text-xs font-bold text-brand-600 shadow-sm transition hover:bg-brand-50"
            >
                <span>+ Isi Saldo</span>
            </button>
        </div>

        <div class="absolute -right-6 -top-6 h-28 w-28 rounded-full bg-white/10 blur-xl"></div>
    </div>

    {{-- Modal Top Up Saldo --}}
    <div id="topup-modal-dash" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 p-4 backdrop-blur-sm">
        <div class="card w-full max-w-md p-6 shadow-2xl">

            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-base font-bold text-gray-900">Isi Saldo E-Kantin</h3>

                <button
                    type="button"
                    data-toggle="#topup-modal-dash"
                    class="text-lg font-bold text-gray-400 hover:text-gray-600"
                >
                    &times;
                </button>
            </div>

            <form method="POST" action="{{ route('student.topup') }}" class="mt-4 space-y-4">
                @csrf

                <div>
                    <label class="label text-xs">Pilihan Cepat</label>

                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                        @foreach ([10000, 20000, 50000, 100000] as $nom)
                            <button
                                type="button"
                                onclick="document.getElementById('amount-input-dash').value = {{ $nom }};"
                                class="rounded-xl border border-gray-200 bg-gray-50 py-2 text-xs font-semibold text-gray-700 hover:border-brand-500 hover:bg-brand-50 hover:text-brand-600"
                            >
                                {{ rupiah($nom) }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label for="amount-input-dash" class="label text-xs">
                        Nominal Lain (Rp)
                    </label>

                    <input
                        id="amount-input-dash"
                        name="amount"
                        type="number"
                        min="5000"
                        max="500000"
                        step="1000"
                        value="20000"
                        class="input font-semibold"
                        required
                    >

                    <p class="mt-1 text-xs text-gray-400">
                        Minimal Rp 5.000 &bull; Maksimal Rp 500.000
                    </p>
                </div>

                <div class="flex gap-2 pt-2">
                    <button
                        type="button"
                        data-toggle="#topup-modal-dash"
                        class="btn-secondary flex-1"
                    >
                        Batal
                    </button>

                    <button type="submit" class="btn-primary flex-1">
                        Konfirmasi Isi
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- 3. Informasi Pesanan Aktif --}}
    @if ($activeOrders->isNotEmpty())
        <section>
            <div class="mb-3 flex items-center justify-between">
                <h2 class="text-sm font-bold text-gray-800">
                    Pesanan Sedang Berjalan
                </h2>

                <a
                    href="{{ route('student.orders.index') }}"
                    class="text-xs font-semibold text-brand-600 hover:underline"
                >
                    Semua Pesanan &rarr;
                </a>
            </div>

            <div class="grid gap-3 sm:grid-cols-2">
                @foreach ($activeOrders as $order)
                    <a
                        href="{{ route('student.orders.show', $order) }}"
                        class="card flex items-center justify-between gap-3 border border-brand-100 bg-white p-4 shadow-sm transition hover:border-brand-300 hover:shadow-md"
                    >
                        <div>
                            <span class="text-[11px] font-semibold text-gray-400">
                                Nomor Antrean
                            </span>

                            <p class="text-2xl font-black tracking-tight text-brand-600">
                                {{ $order->queue_code }}
                            </p>

                            <p class="text-xs font-medium text-gray-600">
                                {{ $order->canteen->name }}
                            </p>
                        </div>

                        <div class="text-right">
                            @include('components.order-status', ['status' => $order->status])

                            <p class="mt-2 text-[11px] font-semibold text-brand-600">
                                Pantau Status &rarr;
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- 4. Kantin yang Tersedia --}}
    <section>
        <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
            <h2 class="text-sm font-bold text-gray-800">
                Kantin Tersedia
            </h2>

            <form
                method="GET"
                action="{{ route('student.dashboard') }}"
                class="flex w-full sm:w-auto"
            >
                <div class="relative w-full sm:w-64">
                    <input
                        name="q"
                        value="{{ $search }}"
                        class="input rounded-xl py-2 pl-9 pr-3 text-xs"
                        placeholder="Cari nama kantin..."
                    >

                    <svg
                        class="absolute left-3 top-2.5 h-4 w-4 text-gray-400"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                        />
                    </svg>
                </div>
            </form>
        </div>

        <div class="grid gap-3 sm:grid-cols-2">

            @forelse ($canteens as $canteen)
                <a
                    href="{{ route('student.canteens.show', $canteen) }}"
                    class="card flex items-center justify-between gap-4 p-4 shadow-sm transition hover:border-brand-300 hover:shadow-md"
                >
                    <div class="flex min-w-0 items-center gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-brand-50 font-black text-brand-600">
                            {{ $canteen->code }}
                        </div>

                        <div class="min-w-0">
                            <p class="truncate font-bold text-gray-900">
                                {{ $canteen->name }}
                            </p>

                            <p class="truncate text-xs text-gray-500">
                                {{ $canteen->description ?: 'Stand kantin sekolah' }}
                            </p>

                            <p class="mt-1 text-[11px] font-semibold text-brand-600">
                                {{ $canteen->products_count }} menu siap dipesan
                            </p>
                        </div>
                    </div>

                    <div class="shrink-0">
                        @include('components.canteen-status', ['status' => $canteen->status])
                    </div>
                </a>

            @empty

                <div class="sm:col-span-2">
                    @include('components.empty', [
                        'title' => 'Kantin tidak ditemukan',
                        'message' => 'Coba kata kunci lain atau tunggu penjual membuka kantin.',
                    ])
                </div>

            @endforelse

        </div>
    </section>

    {{-- 5. Menu Populer / Rekomendasi --}}
    @if ($popularProducts->isNotEmpty())
        <section>
            <div class="mb-3 flex items-center justify-between">
                <h2 class="text-sm font-bold text-gray-800">
                    Menu Populer Siap Dipesan
                </h2>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">

                @foreach ($popularProducts as $product)
                    <div class="card flex flex-col justify-between overflow-hidden shadow-sm transition hover:border-brand-300 hover:shadow-md">

                        <a
                            href="{{ route('student.products.show', $product) }}"
                            class="aspect-[4/3] w-full overflow-hidden bg-gray-100"
                        >
                            @include('components.product-image', [
                                'image' => $product->image,
                                'name' => $product->name
                            ])
                        </a>

                        <div class="flex flex-1 flex-col justify-between p-3.5">
                            <div>

                                <span class="text-[10px] font-semibold text-gray-400">
                                    {{ $product->canteen->name }}
                                </span>

                                <a
                                    href="{{ route('student.products.show', $product) }}"
                                    class="block truncate text-sm font-bold text-gray-900 hover:text-brand-600"
                                >
                                    {{ $product->name }}
                                </a>

                                <p class="mt-1 text-sm font-black text-brand-600">
                                    {{ rupiah($product->price) }}
                                </p>

                            </div>

                            <form
                                method="POST"
                                action="{{ route('student.cart.store', $product) }}"
                                class="mt-3"
                            >
                                @csrf

                                <input
                                    type="hidden"
                                    name="quantity"
                                    value="1"
                                >

                                <button
                                    type="submit"
                                    class="btn-primary w-full rounded-xl py-1.5 text-xs font-bold shadow-none"
                                    @disabled(! $product->isOrderable() || ! $product->canteen->isAcceptingOrders())
                                >
                                    + Tambah
                                </button>
                            </form>

                        </div>
                    </div>
                @endforeach

            </div>
        </section>
    @endif

</div>

@endsection