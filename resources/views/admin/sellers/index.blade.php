@extends('layouts.app')
@section('title', 'Data penjual dan kantin')

@section('content')
<div class="mb-4 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class="text-xl font-bold">Data penjual & kantin</h1>
        <p class="mt-1 text-sm text-gray-500">Satu penjual mengelola satu kantin. Kelola keduanya dari satu halaman.</p>
    </div>
    <a href="{{ route('admin.sellers.create') }}" class="btn-primary">Tambah penjual & kantin</a>
</div>

<div class="table-wrap">
    <table class="min-w-full divide-y divide-gray-200">
        <thead><tr><th class="th">Username</th><th class="th">Penjual</th><th class="th">Kantin</th><th class="th">Status kantin</th><th class="th">Status akun</th><th class="th">Aksi</th></tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($sellers as $seller)
                <tr>
                    <td class="td font-mono">{{ $seller->username }}</td>
                    <td class="td">{{ $seller->name }}</td>
                    <td class="td">{{ $seller->canteen?->name ?? '-' }}</td>
                    <td class="td">
                        @if ($seller->canteen)
                            @include('components.canteen-status', ['status' => $seller->canteen->status])
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="td">
                        <span class="badge {{ $seller->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $seller->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="td">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.sellers.edit', $seller) }}" class="text-brand-600 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.sellers.destroy', $seller) }}" data-confirm="Hapus penjual {{ $seller->name }}?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td class="td text-gray-500" colspan="6">Belum ada penjual terdaftar.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $sellers->links() }}</div>
@endsection
