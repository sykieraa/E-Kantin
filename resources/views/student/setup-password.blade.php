
@extends('layouts.guest')

@section('title', 'Buat Password')

@section('content')
<div class="flex min-h-screen items-center justify-center px-6 py-12">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-sm">
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-900">Buat Password</h1>
            <p class="mt-2 text-sm text-gray-500">
                Buat password baru untuk akun E-Kantin kamu.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST"
              action="{{ route('student.activation.password.store') }}"
              class="space-y-5">
            @csrf

            {{-- Password Baru --}}
            <div>
                <label for="password" class="mb-1 block text-sm font-medium text-gray-700">
                    Password Baru
                </label>

                <div class="relative">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="input w-full pr-12"
                        placeholder="Minimal 8 karakter"
                        autocomplete="new-password"
                        required
                    >

                    <button
                        type="button"
                        class="absolute inset-y-0 right-1 grid w-11 place-items-center rounded-lg text-gray-500 hover:text-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500"
                        data-password-toggle="#password"
                        aria-controls="password"
                        aria-pressed="false"
                        aria-label="Tampilkan password"
                        title="Tampilkan password"
                    >
                        <svg data-eye-open viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="h-5 w-5" aria-hidden="true">
                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>

                        <svg data-eye-closed viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="hidden h-5 w-5" aria-hidden="true">
                            <path d="m3 3 18 18" />
                            <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8" />
                            <path d="M9.9 4.2A10.8 10.8 0 0 1 12 4c6.5 0 10 8 10 8a18.2 18.2 0 0 1-3.2 4.1" />
                            <path d="M6.6 6.6C3.7 8.6 2 12 2 12s3.5 8 10 8a9.8 9.8 0 0 0 3.4-.6" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Konfirmasi Password --}}
            <div>
                <label for="password_confirmation" class="mb-1 block text-sm font-medium text-gray-700">
                    Konfirmasi Password
                </label>

                <div class="relative">
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        class="input w-full pr-12"
                        placeholder="Ulangi password"
                        autocomplete="new-password"
                        required
                    >

                    <button
                        type="button"
                        class="absolute inset-y-0 right-1 grid w-11 place-items-center rounded-lg text-gray-500 hover:text-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500"
                        data-password-toggle="#password_confirmation"
                        aria-controls="password_confirmation"
                        aria-pressed="false"
                        aria-label="Tampilkan password"
                        title="Tampilkan password"
                    >
                        <svg data-eye-open viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="h-5 w-5" aria-hidden="true">
                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>

                        <svg data-eye-closed viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="hidden h-5 w-5" aria-hidden="true">
                            <path d="m3 3 18 18" />
                            <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8" />
                            <path d="M9.9 4.2A10.8 10.8 0 0 1 12 4c6.5 0 10 8 10 8a18.2 18.2 0 0 1-3.2 4.1" />
                            <path d="M6.6 6.6C3.7 8.6 2 12 2 12s3.5 8 10 8a9.8 9.8 0 0 0 3.4-.6" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-primary w-full">
                Simpan Password
            </button>
        </form>
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
        button.setAttribute(
            'aria-label',
            isVisible ? 'Tampilkan password' : 'Sembunyikan password'
        );
        button.setAttribute(
            'title',
            isVisible ? 'Tampilkan password' : 'Sembunyikan password'
        );

        button.querySelector('[data-eye-open]')
            ?.classList.toggle('hidden', !isVisible);

        button.querySelector('[data-eye-closed]')
            ?.classList.toggle('hidden', isVisible);
    });
</script>
@endsection