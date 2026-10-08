@extends('layouts.app')
@section('title', 'Edit penjual')

@section('content')
<a href="{{ route('admin.sellers.index') }}" class="text-sm text-gray-500 hover:underline">&larr; Data penjual & kantin</a>
<h1 class="mb-4 mt-1 text-xl font-bold">Edit {{ $seller->name }} & kantinnya</h1>

<form method="POST" action="{{ route('admin.sellers.update', $seller) }}" class="card max-w-xl space-y-4 p-5">
    @include('admin.sellers.form', ['seller' => $seller])
    <button type="submit" class="btn-primary">Simpan perubahan</button>
</form>
@endsection
