@extends('layouts.app')
@section('title', 'Tambah siswa')

@section('content')
<a href="{{ route('admin.students.index') }}" class="text-sm text-gray-500 hover:underline">&larr; Data siswa</a>
<h1 class="mb-4 mt-1 text-xl font-bold">Tambah siswa</h1>

<form method="POST" action="{{ route('admin.students.store') }}" class="card max-w-xl space-y-4 p-5">
    @include('admin.students.form')
    <button type="submit" class="btn-primary">Simpan</button>
</form>
@endsection
