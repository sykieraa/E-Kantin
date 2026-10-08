@extends('layouts.app')
@section('title', 'Tambah penjual dan kantin')

@section('content')
<a href="{{ route('admin.sellers.index') }}" class="text-sm text-gray-500 hover:underline">&larr; Data penjual & kantin</a>
<h1 class="mb-1 mt-1 text-xl font-bold">Tambah penjual & kantin</h1>
<p class="mb-4 text-sm text-gray-500">Akun penjual dan data kantin dibuat sekaligus.</p>

<form method="POST" action="{{ route('admin.sellers.store') }}" class="card max-w-xl space-y-4 p-5">
    @include('admin.sellers.form')
    <button type="submit" class="btn-primary">Simpan</button>
</form>
@endsection
