@php
    // Kelas ditulis lengkap (tidak dirangkai string) supaya terdeteksi Tailwind saat build.
    $styles = [
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
        'error' => 'border-red-200 bg-red-50 text-red-800',
        'status' => 'border-blue-200 bg-blue-50 text-blue-800',
    ];
@endphp

@foreach ($styles as $key => $classes)
    @if (session($key))
        <div data-alert class="mb-4 flex items-start justify-between gap-3 rounded-xl border px-4 py-3 text-sm {{ $classes }}">
            <p>{{ session($key) }}</p>
            <button type="button" data-dismiss class="shrink-0 text-lg font-bold leading-none opacity-60">&times;</button>
        </div>
    @endif
@endforeach

@if ($errors->any())
    <div data-alert class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <p class="mb-1 font-semibold">Periksa kembali isian berikut:</p>
        <ul class="list-inside list-disc space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
