{{-- Navigasi bawah, mengikuti kebiasaan aplikasi pesan makanan di ponsel. --}}
<nav class="fixed inset-x-0 bottom-0 z-20 border-t border-gray-200 bg-white sm:hidden">
    <div class="mx-auto grid max-w-5xl grid-cols-3">
        @php
            $tabs = [
                [
                    'route' => 'student.dashboard',
                    'label' => 'Kantin',
                    'icon' => 'home',
                ],
                [
                    'route' => 'student.cart.index',
                    'label' => 'Keranjang',
                    'icon' => 'cart',
                ],
                [
                    'route' => 'student.orders.index',
                    'label' => 'Pesanan',
                    'icon' => 'receipt',
                ],
            ];
        @endphp

        @foreach ($tabs as $tab)
            <a
                href="{{ route($tab['route']) }}"
                aria-label="{{ $tab['label'] }}"
                title="{{ $tab['label'] }}"
                class="relative grid place-items-center py-3 {{ request()->routeIs($tab['route']) ? 'text-brand-600' : 'text-gray-500' }}"
            >
                @include('components.icon.' . $tab['icon'], ['class' => 'h-6 w-6'])

                @if ($tab['route'] === 'student.cart.index' && ($cartCount ?? 0) > 0)
                    <span class="absolute ml-6 -mt-6 grid h-4 min-w-4 place-items-center rounded-full bg-brand-600 px-1 text-[10px] font-bold text-white">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>
        @endforeach
    </div>
</nav>