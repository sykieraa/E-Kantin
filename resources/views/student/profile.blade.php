@extends('layouts.app')
@section('title', 'Profil Siswa')

@section('content')
<div class="mx-auto max-w-xl space-y-5">
    {{-- Header Profil --}}
    <div class="card overflow-hidden border-0 bg-gradient-to-br from-brand-500 to-brand-600 p-6 text-white shadow-md">
        <div class="flex items-center gap-4">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/20 text-2xl font-black shadow-inner backdrop-blur-md">
                {{ strtoupper(substr($student?->name ?? $user->name, 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <span class="inline-block rounded-full bg-white/20 px-2.5 py-0.5 text-xs font-semibold backdrop-blur-md">Siswa</span>
                <h1 class="truncate text-xl font-extrabold tracking-tight text-white">{{ $student?->name ?? $user->name }}</h1>
                <p class="text-xs text-brand-100">{{ $student?->class_name }} &bull; NIS: {{ $student?->nis }}</p>
            </div>
        </div>
    </div>

    {{-- Kartu Saldo E-Kantin --}}
    <div class="card flex flex-wrap items-center justify-between gap-4 p-5 shadow-sm">
        <div class="flex items-center gap-3.5">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-50 text-brand-600">
                <x-icon.wallet class="h-6 w-6" />
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Saldo E-Kantin</p>
                <p class="text-2xl font-extrabold tracking-tight text-gray-900">{{ rupiah($student?->balance ?? 0) }}</p>
            </div>
        </div>
        <button type="button" data-toggle="#topup-modal" class="btn-primary rounded-xl px-4 py-2.5 text-sm">
            + Isi Saldo
        </button>
    </div>

    {{-- Modal Top Up --}}
    <div id="topup-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 p-4 backdrop-blur-sm">
        <div class="card w-full max-w-md p-6 shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-base font-bold text-gray-900">Isi Saldo E-Kantin</h3>
                <button type="button" data-toggle="#topup-modal" class="text-gray-400 hover:text-gray-600 text-lg font-bold">&times;</button>
            </div>
            <form method="POST" action="{{ route('student.topup') }}" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="label text-xs">Pilih Nominal Cepat</label>
                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                        @foreach ([10000, 20000, 50000, 100000] as $nom)
                            <button type="button" onclick="document.getElementById('amount-input').value = {{ $nom }};"
                                    class="rounded-xl border border-gray-200 bg-gray-50 py-2 text-xs font-semibold text-gray-700 hover:border-brand-500 hover:bg-brand-50 hover:text-brand-600">
                                {{ rupiah($nom) }}
                            </button>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label for="amount-input" class="label text-xs">Nominal Lain (Rp)</label>
                    <input id="amount-input" name="amount" type="number" min="5000" max="500000" step="1000"
                           value="20000" class="input font-semibold" required>
                    <p class="mt-1 text-xs text-gray-400">Minimal Rp 5.000 &bull; Maksimal Rp 500.000</p>
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="button" data-toggle="#topup-modal" class="btn-secondary flex-1">Batal</button>
                    <button type="submit" class="btn-primary flex-1">Konfirmasi Isi</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Ringkasan Pesanan --}}
    <div class="card p-5 shadow-sm">
        <h2 class="text-sm font-bold text-gray-800">Aktivitas Pemesanan</h2>
        <div class="mt-4 grid grid-cols-3 gap-3 text-center">
            <div class="rounded-2xl bg-gray-50 p-3">
                <p class="text-xs text-gray-500">Total</p>
                <p class="mt-1 text-xl font-extrabold text-gray-900">{{ $stats['total_orders'] }}</p>
            </div>
            <div class="rounded-2xl bg-brand-50/70 p-3">
                <p class="text-xs text-brand-700 font-medium">Sedang Aktif</p>
                <p class="mt-1 text-xl font-extrabold text-brand-600">{{ $stats['active_orders'] }}</p>
            </div>
            <div class="rounded-2xl bg-emerald-50 p-3">
                <p class="text-xs text-emerald-700 font-medium">Selesai</p>
                <p class="mt-1 text-xl font-extrabold text-emerald-600">{{ $stats['completed_orders'] }}</p>
            </div>
        </div>
    </div>

    {{-- Informasi Keamanan Akun & Password --}}
    <div class="rounded-2xl border border-gray-200/70 bg-white p-5 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gray-100 text-gray-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-900">Keamanan & Password Akun</p>
                <p class="mt-1 text-xs leading-relaxed text-gray-500">
                    Sesuai kebijakan E-Kantin sekolah, pengelolaan dan reset password akun murid dikelola langsung oleh <strong>Administrator Sekolah</strong>. Hubungi petugas lab atau wali kelas jika Anda membutuhkan reset akun.
                </p>
            </div>
        </div>
    </div>

    {{-- Tombol Keluar --}}
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-danger w-full justify-center rounded-2xl py-3 text-sm font-bold">
            Keluar dari Akun
        </button>
    </form>
</div>
@endsection

