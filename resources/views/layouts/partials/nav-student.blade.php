<header class="sticky top-0 z-20 border-b border-gray-200 bg-white/95 backdrop-blur">
    <div class="mx-auto flex w-full max-w-5xl items-center justify-between gap-3 px-4 py-3">
        <a href="{{ route('student.dashboard') }}" class="flex items-center gap-2">
            <span class="grid h-9 w-9 place-items-center rounded-xl bg-brand-600 text-white">
                @include('components.icon.cutlery', ['class' => 'h-5 w-5'])
            </span>
            <span class="text-base font-bold tracking-tight">{{ config('app.name') }}</span>
        </a>

        <div class="flex items-center gap-2">
            @include('layouts.partials.account-menu')
        </div>
    </div>
</header>
