@extends('layouts.app')

@section('title', 'Aktivasi Akun')

@section('content')
<div class="mx-auto max-w-md py-8">

    <div class="rounded-2xl bg-white p-6 shadow-sm">
        <div class="mb-6 text-center">
            <h1 class="text-xl font-bold text-gray-900">
                Aktivasi Akun
            </h1>

            <p class="mt-2 text-sm text-gray-500">
                Masukkan NIS dan kode aktivasi yang diberikan oleh admin.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('student.activation.verify') }}"
            class="space-y-5"
        >
            @csrf

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">
                    NIS
                </label>

                <input
                    type="text"
                    name="nis"
                    value="{{ old('nis') }}"
                    class="input w-full"
                    placeholder="Masukkan NIS"
                    required
                >
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Kode Aktivasi
                </label>

                <input
                    type="text"
                    name="code"
                    value="{{ old('code') }}"
                    maxlength="8"
                    class="input w-full uppercase"
                    placeholder="Contoh: X7KP29QM"
                    required
                >

                <p class="mt-1 text-xs text-gray-500">
                    Kode berlaku selama 24 jam.
                </p>
            </div>

            <button
                type="submit"
                class="btn-primary w-full"
            >
                Lanjutkan
            </button>
        </form>

        <div class="mt-5 text-center">
            <a
                href="{{ route('login') }}"
                class="text-sm text-gray-500 hover:underline"
            >
                Kembali ke login
            </a>
        </div>
    </div>

</div>
@endsection