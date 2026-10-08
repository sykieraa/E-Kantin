<div class="relative">
    <button
        type="button"
        data-toggle="#account-menu"
        aria-label="Buka menu profil"
        title="Profil"
        class="grid h-10 w-10 place-items-center rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50"
    >
        @include('components.icon.menu', ['class' => 'h-5 w-5'])
    </button>

    <div id="account-menu" class="absolute right-0 z-30 mt-2 hidden w-52 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg">
        <div class="border-b border-gray-100 px-4 py-3">
            <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>

            <p class="text-xs text-gray-500">
                @if (auth()->user()->isStudent())
                    {{ auth()->user()->student?->class_name }} &middot; {{ auth()->user()->student?->nis }}
                @else
                    {{ ucfirst(auth()->user()->role) }}
                @endif
            </p>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                type="submit"
                class="w-full px-4 py-2.5 text-left text-sm text-red-600 hover:bg-red-50"
            >
                Keluar
            </button>
        </form>
    </div>
</div>