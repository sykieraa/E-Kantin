@extends('layouts.app')
@section('title', 'Status kantin')

@section('content')
<a href="{{ route('seller.dashboard') }}" class="text-sm text-gray-500 hover:underline">&larr; Kembali ke dashboard</a>
<h1 class="mb-1 mt-1 text-xl font-bold">Status kantin</h1>
<p class="mb-5 text-sm text-gray-500">Hanya status <strong>Buka</strong> yang membuat siswa bisa membuat pesanan baru.</p>

<form method="POST" action="{{ route('seller.canteen.status.update') }}" class="card w-full space-y-4 p-5">
    @csrf
    @method('PUT')

    <div class="space-y-2">
        @php
            $options = [
                'open' => ['Buka', 'Siswa bisa memesan seperti biasa.'],
                'temporarily_closed' => ['Tutup sementara', 'Menu tetap terlihat, tapi pesanan baru ditolak. Cocok saat sedang restock. Stok tidak berubah otomatis.'],
                'closed' => ['Tutup', 'Kantin tidak beroperasi hari ini.'],
            ];
        @endphp

        @foreach ($options as $value => [$label, $hint])
            <label class="flex cursor-pointer items-start gap-3 rounded-xl border p-4 transition
                {{ old('status', $canteen->status) === $value ? 'border-brand-500 bg-brand-50' : 'border-gray-200 hover:bg-gray-50' }}">
                <input type="radio" name="status" value="{{ $value }}" class="mt-1 text-brand-600 focus:ring-brand-500"
                       @checked(old('status', $canteen->status) === $value)>
                <span>
                    <span class="block font-semibold">{{ $label }}</span>
                    <span class="block text-sm text-gray-500">{{ $hint }}</span>
                </span>
            </label>
        @endforeach
    </div>

    <div>
        <label for="description" class="label">Keterangan kantin</label>
        <textarea id="description" name="description" rows="3" class="input">{{ old('description', $canteen->description) }}</textarea>
        <p class="mt-1 text-xs text-gray-500">Ditampilkan ke siswa di daftar kantin.</p>
    </div>

    <p class="rounded-xl bg-gray-50 px-4 py-3 text-sm text-gray-600">
        Saat restock, kamu tidak wajib menginput stok baru. Perbarui stok hanya jika jumlah persediaan memang berubah, melalui menu <strong>Menu</strong>.
    </p>

    <button type="submit" class="btn-primary">Simpan perubahan</button>
</form>
@endsection
