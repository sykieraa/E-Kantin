@extends('layouts.app')
@section('title', 'Edit siswa')

@section('content')
<a href="{{ route('admin.students.index') }}" class="text-sm text-gray-500 hover:underline">&larr; Data siswa</a>
<h1 class="mb-4 mt-1 text-xl font-bold">Edit {{ $student->name }}</h1>

<form method="POST" action="{{ route('admin.students.update', $student) }}" class="card max-w-xl space-y-4 p-5">
    @include('admin.students.form', ['student' => $student])
    <button type="submit" class="btn-primary">Simpan perubahan</button>
</form>
@endsection
