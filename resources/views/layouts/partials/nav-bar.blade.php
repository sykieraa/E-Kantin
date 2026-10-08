<header class="sticky top-0 z-20 border-b border-gray-200 bg-white">
    <div class="mx-auto flex w-full max-w-5xl items-center justify-between gap-3 px-4 py-3">
        <a href="{{ $home }}" class="flex items-center gap-2">
            <span class="grid h-9 w-9 place-items-center rounded-xl bg-brand-600 text-white">
                @include('components.icon.cutlery', ['class' => 'h-5 w-5'])
            </span>

            <span class="text-base font-bold tracking-tight">
                {{ config('app.name') }}
            </span>
        </a>

        <div class="flex items-center gap-2">
            @include('layouts.partials.account-menu')
        </div>
    </div>

    <nav id="main-nav" class="hidden border-t border-gray-100 md:block">
        <div class="mx-auto flex w-full max-w-5xl flex-col gap-1 px-4 py-2 md:flex-row md:gap-1 md:py-0">
            @foreach ($links as $link)
                @php $active = request()->routeIs($link['match']); @endphp

                <a href="{{ route($link['route']) }}"
                   @class([
                       'rounded-lg px-3 py-2 text-sm font-medium md:rounded-none md:border-b-2 md:py-3',
                       'bg-brand-50 text-brand-700 md:border-brand-600 md:bg-transparent' => $active,
                       'text-gray-600 hover:bg-gray-50 md:border-transparent' => ! $active,
                   ])>
                    {{ $link['label'] }}
                </a>
            @endforeach
        </div>
    </nav>
</header>