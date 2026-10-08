@php
    $map = [
        'pending' => ['Menunggu', 'bg-amber-50 text-amber-700', 'bg-amber-500'],
        'processing' => ['Diproses', 'bg-blue-50 text-blue-700', 'bg-blue-500'],
        'ready' => ['Siap diambil', 'bg-brand-50 text-brand-700', 'bg-brand-500'],
        'completed' => ['Selesai', 'bg-emerald-50 text-emerald-700', 'bg-emerald-500'],
        'cancelled' => ['Dibatalkan', 'bg-gray-100 text-gray-600', 'bg-gray-400'],
    ];
    [$label, $classes, $dot] = $map[$status] ?? $map['pending'];
@endphp
<span class="badge {{ $classes }}">
    <span class="h-2 w-2 rounded-full {{ $dot }}"></span>{{ $label }}
</span>
