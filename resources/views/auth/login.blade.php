@extends('layouts.guest')
@section('title', 'Masuk')

@section('content')
<div class="grid min-h-screen lg:grid-cols-2">
    {{-- Sisi kiri: identitas aplikasi. Nomor antrean dipakai sebagai elemen utama
         karena itulah janji produk ini: pesan dulu, ambil tanpa antre. --}}
    <div class="flex flex-col justify-between bg-brand-600 px-6 py-10 text-white lg:px-12">
        <div class="flex items-center gap-2">
            <span class="grid h-10 w-10 place-items-center rounded-xl bg-white/20">
                @include('components.icon.cutlery', ['class' => 'h-6 w-6'])
            </span>
            <span class="text-lg font-bold">E-Kantin</span>
        </div>

        <div class="my-10">
            <p class="text-3xl font-extrabold leading-tight sm:text-4xl">SMK Plus Pelita Nusantara</p>
            <p class="mt-3 max-w-sm text-brand-100">Layanan kantin digital</p>
        </div>

        <p class="text-xs text-brand-200">© E-Kantin 2026 &middot; Developed by <a href="https://github.com/sykieraa" target="_blank" rel="noopener noreferrer" class="font-semibold hover:underline">
            @sykieraa
        </a></p>
    </div>

    {{-- Sisi kanan: form login --}}
    <div class="flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-sm">
            <h1 class="text-2xl font-bold">Masuk</h1>
            <p class="mt-1 text-sm text-gray-500">Siswa memakai NIS. Penjual dan admin memakai username.</p>

            @include('components.flash')

            <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="username" class="label">NIS</label>
                    <input id="username" name="username" value="{{ old('username') }}" class="input" autofocus required
                           placeholder="contoh: 2024001">
                </div>

                <div>
                    <label for="password" class="label">Password</label>
                    <div class="relative">
                        <input id="password" name="password" type="password" class="input pr-12" required
                               placeholder="Password kamu" autocomplete="current-password" spellcheck="false">
                        <button type="button" class="absolute inset-y-0 right-1 grid w-11 place-items-center rounded-lg text-gray-500 hover:text-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500"
                                data-password-toggle="#password" aria-controls="password" aria-pressed="false"
                                aria-label="Tampilkan password" title="Tampilkan password">
                            <svg data-eye-open viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            <svg data-eye-closed viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hidden h-5 w-5" aria-hidden="true">
                                <path d="m3 3 18 18" />
                                <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8" />
                                <path d="M9.9 4.2A10.8 10.8 0 0 1 12 4c6.5 0 10 8 10 8a18.2 18.2 0 0 1-3.2 4.1" />
                                <path d="M6.6 6.6C3.7 8.6 2 12 2 12s3.5 8 10 8a9.8 9.8 0 0 0 3.4-.6" />
                            </svg>
                            <span class="sr-only">Tampilkan password</span>
                        </button>
                    </div>
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember" value="1" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                    Ingat saya di perangkat ini
                </label>

                <button type="submit" class="btn-primary w-full">Masuk</button>
</form>

<div class="mt-5 text-center">
    <p class="text-sm text-gray-500">
        Belum punya password?
        <a
            href="{{ route('student.activation') }}"
            class="font-semibold text-brand-600 hover:text-brand-700 hover:underline"
        >
            Aktivasi Akun
        </a>
    </p>
</div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('click', (event) => {
        const button = event.target.closest('[data-password-toggle]');
        if (!button) return;

        const field = document.querySelector(button.dataset.passwordToggle);
        if (!field) return;

        const isVisible = field.type === 'text';
        field.type = isVisible ? 'password' : 'text';
        button.setAttribute('aria-pressed', String(!isVisible));
        button.setAttribute('aria-label', isVisible ? 'Tampilkan password' : 'Sembunyikan password');
        button.setAttribute('title', isVisible ? 'Tampilkan password' : 'Sembunyikan password');
        button.querySelector('[data-eye-open]')?.classList.toggle('hidden', !isVisible);
        button.querySelector('[data-eye-closed]')?.classList.toggle('hidden', isVisible);
        button.querySelector('.sr-only').textContent = isVisible ? 'Tampilkan password' : 'Sembunyikan password';
    });
</script>
@endsection
