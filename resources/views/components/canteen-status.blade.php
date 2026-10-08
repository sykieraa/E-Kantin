@php
    $map = [
        'open' => ['Buka', 'bg-emerald-50 text-emerald-700', 'bg-emerald-500'],
        'temporarily_closed' => ['Tutup sementara', 'bg-amber-50 text-amber-700', 'bg-amber-500'],
        'closed' => ['Tutup', 'bg-red-50 text-red-700', 'bg-red-500'],
    ];
    [$label, $classes, $dot] = $map[$status] ?? $map['closed'];
@endphp
<span class="badge {{ $classes }}">
    <span class="h-2 w-2 rounded-full {{ $dot }}"></span>{{ $label }}
</span>
