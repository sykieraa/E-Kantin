@extends('layouts.app')
@section('title', 'Kategori menu')

@section('content')
<h1 class="mb-4 text-xl font-bold">Kategori menu</h1>

<form method="POST" action="{{ route('admin.categories.store') }}" class="card mb-4 flex flex-wrap items-end gap-3 p-4">
    @csrf
    <div class="flex-1 sm:max-w-xs">
        <label for="name" class="label">Nama kategori baru</label>
        <input id="name" name="name" class="input" required placeholder="contoh: Minuman">
    </div>
    <button type="submit" class="btn-primary">Tambah</button>
</form>

<div class="table-wrap">
    <table class="min-w-full divide-y divide-gray-200">
        <thead><tr><th class="th">Nama</th><th class="th">Jumlah menu</th><th class="th">Aksi</th></tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($categories as $category)
                <tr>
                    <td class="td">
                        <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="flex items-center gap-2">
                            @csrf
                            @method('PUT')
                            <input name="name" value="{{ $category->name }}" class="input w-48 px-2 py-1.5 text-sm">
                            <button type="submit" class="btn-secondary px-3 py-1.5 text-xs">Simpan</button>
                        </form>
                    </td>
                    <td class="td">{{ $category->products_count }}</td>
                    <td class="td">
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                              data-confirm="Hapus kategori {{ $category->name }}? Menu yang memakainya akan menjadi tanpa kategori.">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td class="td text-gray-500" colspan="3">Belum ada kategori.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $categories->links() }}</div>
@endsection
