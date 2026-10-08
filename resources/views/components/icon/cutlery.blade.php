@props(['class' => 'h-5 w-5'])
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" {{ $attributes->merge(['class' => $class]) }}>
    {{-- Garpu --}}
    <path d="M5 2v7c0 1.66 1.34 3 3 3h0c1.66 0 3-1.34 3-3V2" />
    <path d="M8 2v7" />
    <path d="M8 12v10" />
    {{-- Sendok --}}
    <path d="M16 2c-1.66 0-3 1.79-3 4s1.34 4 3 4 3-1.79 3-4-1.34-4-3-4z" />
    <path d="M16 10v12" />
</svg>

