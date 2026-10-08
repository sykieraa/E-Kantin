@extends('layouts.app')

@section('title', 'Data siswa')

@section('content')
<div class="mb-4 flex flex-wrap items-center justify-between gap-3">
    <h1 class="text-xl font-bold">Data siswa</h1>

    <a
        href="{{ route('admin.students.create') }}"
        class="btn-primary"
    >
        Tambah siswa
    </a>
</div>

{{-- KARTU KODE AKTIVASI --}}
@if (session('activation'))
    @php
        $activation = session('activation');

        $message = implode("\n", [
            "Halo {$activation['name']}, ini kode aktivasi akun E-Kantin kamu: {$activation['code']}",
            'Berlaku 24 jam dan hanya bisa dipakai sekali.',
            'Buka ' . $activation['activation_url'] . ', lalu masukkan NIS ' . $activation['nis'] . ' dan kode ini untuk membuat password.',
            'Jangan bagikan kode ini ke siapa pun.',
        ]);
    @endphp

    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4">
        <p class="text-sm font-semibold text-green-800">
            Kode Aktivasi untuk: {{ $activation['name'] }}
        </p>

        <p class="mt-1 text-sm text-green-800">
            NIS: {{ $activation['nis'] }}
            &middot;
            Kelas: {{ $activation['class'] }}
        </p>

        <div class="mt-3 flex flex-wrap gap-2">
            <input
                type="text"
                value="{{ $activation['code'] }}"
                readonly
                class="input min-w-0 flex-1 font-mono font-bold tracking-widest"
                id="activation-code"
            >

            <button
                type="button"
                onclick="navigator.clipboard.writeText(document.getElementById('activation-code').value)"
                class="btn-primary"
            >
                Salin
            </button>
        </div>

        @if (!empty($activation['wa_number']))
            <a
                href="https://wa.me/{{ $activation['wa_number'] }}?text={{ rawurlencode($message) }}"
                target="_blank"
                rel="noopener noreferrer"
                class="mt-3 inline-flex items-center justify-center rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-black transition hover:bg-green-700"
            >
                Kirim via WhatsApp
            </a>
        @else
            <button
                type="button"
                disabled
                class="mt-3 cursor-not-allowed rounded-lg bg-gray-300 px-4 py-2 text-sm font-semibold text-gray-600"
            >
                Kirim via WhatsApp
            </button>

            <p class="mt-2 text-xs text-red-700">
                Nomor WA belum diisi. Salin kode dan berikan secara manual.
            </p>
        @endif

        <p class="mt-2 text-xs text-green-700">
            Kode berlaku 24 jam dan hanya dapat digunakan sekali.
        </p>
    </div>
@endif

<form method="GET" class="mb-4 flex gap-2">
    <input
        name="q"
        value="{{ request('q') }}"
        class="input sm:max-w-xs"
        placeholder="Cari nama atau NIS"
    >

    <button
        type="submit"
        class="btn-secondary"
    >
        Cari
    </button>
</form>

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead>
            <tr>
                <th class="th">NIS</th>
                <th class="th">Nama</th>
                <th class="th">Kelas</th>
                <th class="th">No. WhatsApp</th>
                <th class="th">Aksi</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-100">
            @forelse ($students as $student)
                <tr>
                    <td class="td font-mono">
                        {{ $student->nis }}
                    </td>

                    <td class="td">
                        {{ $student->name }}
                    </td>

                    <td class="td">
                        {{ $student->class_name }}
                    </td>

                    <td class="td">
                        {{ $student->phone ?: '-' }}
                    </td>

                    <td class="td">
                        <div class="flex flex-wrap gap-2">

                            {{-- Edit --}}
                            <a
                                href="{{ route('admin.students.edit', $student) }}"
                                class="inline-flex items-center justify-center rounded-md bg-[#facc15] px-4 py-2 text-sm font-medium text-black transition hover:bg-[#eab308]"
                            >
                                Edit
                            </a>

                            {{-- Reset Password --}}
                            <form
                                method="POST"
                                action="{{ route('admin.students.reset-password', $student) }}"
                                data-confirm="Reset password siswa {{ $student->name }}?"
                            >
                                @csrf

                                
                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center rounded-md bg-[#3b82f6] px-4 py-2 text-sm font-medium text-black transition hover:bg-[#2563eb]"
                                >
                                    Reset Password
                                </button>
                            </form>

                            {{-- Aktivasi --}}
                            <form
                                method="POST"
                                action="{{ route('admin.students.activation', $student) }}"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="rounded-lg bg-[#22c55e] px-4 py-2 text-sm font-medium text-black hover:bg-[#16a34a]"
                                >
                                    Aktivasi
                                </button>
                            </form>

                            {{-- Hapus --}}
                            <form
                                method="POST"
                                action="{{ route('admin.students.destroy', $student) }}"
                                data-confirm="Hapus siswa {{ $student->name }}?"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center rounded-md bg-[#ef4444] px-4 py-2 text-sm font-medium text-black transition hover:bg-[#dc2626]"
                                >
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td
                        class="td text-gray-500"
                        colspan="4"
                    >
                        Belum ada data siswa.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $students->links() }}
</div>
@endsection