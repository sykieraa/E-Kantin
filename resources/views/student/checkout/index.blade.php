@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<a href="{{ route('student.cart.index') }}" class="text-sm text-gray-500 hover:underline">
    &larr; Kembali ke keranjang
</a>

<h1 class="mb-4 mt-1 text-xl font-bold">Periksa pesanan</h1>

@foreach ($issues as $issue)
    <p class="mb-2 rounded-xl border border-amber-200 bg-amber-50 px-4 py-2.5 text-sm text-amber-800">
        {{ $issue }}
    </p>
@endforeach

<div class="card p-5">

    {{-- Informasi pesanan --}}
    <dl class="space-y-2 border-b border-gray-100 pb-4 text-sm">

        <div class="flex justify-between gap-4">
            <dt class="text-gray-500">Kantin</dt>
            <dd class="font-semibold">{{ $canteen?->name }}</dd>
        </div>

        <div class="flex justify-between gap-4">
            <dt class="text-gray-500">Pemesan</dt>
            <dd class="font-semibold">
                {{ auth()->user()->student?->name }}
                &middot;
                {{ auth()->user()->student?->class_name }}
            </dd>
        </div>

        <div class="flex justify-between gap-4">
            <dt class="text-gray-500">Waktu pemesanan</dt>
            <dd class="font-semibold">
                {{ now()->translatedFormat('d F Y, H:i') }}
            </dd>
        </div>

    </dl>

    {{-- Daftar menu --}}
    <ul class="divide-y divide-gray-100 py-2">
        @foreach ($lines as $line)
            <li class="flex items-center justify-between gap-4 py-3 text-sm">
                <span>
                    {{ $line['product']->name }}
                    <span class="text-gray-400">
                        &times; {{ $line['quantity'] }}
                    </span>
                </span>

                <span class="font-semibold">
                    {{ rupiah($line['subtotal']) }}
                </span>
            </li>
        @endforeach
    </ul>

    {{-- Total --}}
    <div class="flex items-center justify-between border-t border-gray-100 pt-4">
        <p class="text-sm text-gray-600">
            Total bayar
        </p>

        <p class="text-2xl font-extrabold text-brand-600">
            {{ rupiah($total) }}
        </p>
    </div>

    {{-- Metode pembayaran --}}
    <form
        method="POST"
        action="{{ route('student.checkout.store') }}"
        class="mt-5"
    >
        @csrf

        <fieldset class="mb-5">

            <legend class="label">
                Pilih metode pembayaran
            </legend>

            <div class="space-y-2">

                {{-- Saldo E-Kantin --}}
                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 px-4 py-3 hover:bg-gray-50">

                    <input
                        type="radio"
                        name="payment_method"
                        value="{{ \App\Models\Order::PAYMENT_BALANCE }}"
                        class="mt-1 text-brand-600 focus:ring-brand-500"
                        @checked(old('payment_method', \App\Models\Order::PAYMENT_BALANCE) === \App\Models\Order::PAYMENT_BALANCE)
                    >

                    <span>
                        <span class="block text-sm font-semibold">
                            Saldo E-Kantin
                        </span>

                        <span class="mt-0.5 block text-xs text-gray-500">
                            Saldo tersedia:
                            <strong class="text-gray-700">
                                {{ rupiah(auth()->user()->student?->balance ?? 0) }}
                            </strong>
                        </span>
                    </span>

                </label>

                {{-- Bayar di outlet --}}
                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 px-4 py-3 hover:bg-gray-50">

                    <input
                        type="radio"
                        name="payment_method"
                        value="{{ \App\Models\Order::PAYMENT_OUTLET }}"
                        class="mt-1 text-brand-600 focus:ring-brand-500"
                        @checked(old('payment_method') === \App\Models\Order::PAYMENT_OUTLET)
                    >

                    <span>
                        <span class="block text-sm font-semibold">
                            Bayar di outlet
                        </span>

                        <span class="mt-0.5 block text-xs text-gray-500">
                            Bayar langsung ke penjual saat mengambil pesanan.
                            Bisa menggunakan tunai atau QRIS yang tersedia di outlet.
                        </span>
                    </span>

                </label>

            </div>

            @error('payment_method')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </fieldset>

        <button
            type="submit"
            class="btn-primary w-full"
            @disabled(count($issues) > 0 || ! $canteen?->isAcceptingOrders())
        >
            Buat pesanan
        </button>
    </form>

    <p class="mt-3 text-xs text-gray-500">
        Jika memilih Saldo E-Kantin, saldo akan langsung dipotong sesuai total pesanan.
        Jika memilih Bayar di outlet, pembayaran dilakukan langsung kepada penjual.
    </p>

</div>
@endsection