@extends('layouts.app')
@section('title', 'Tambah menu')

@section('content')
<a href="{{ route('seller.products.index') }}" class="text-sm text-gray-500 hover:underline">&larr; Kelola menu</a>
<h1 class="mb-4 mt-1 text-xl font-bold">Tambah menu</h1>

<form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data" class="card w-full space-y-4 p-5">
    @include('seller.products.form', ['categories' => $categories])
    <button type="submit" class="btn-primary">Simpan menu</button>
</form>
@endsection
