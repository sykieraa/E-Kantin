<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beranda') &middot; {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
    @auth
        @if (auth()->user()->isStudent())
            @include('layouts.partials.nav-student')
        @elseif (auth()->user()->isSeller())
            @include('layouts.partials.nav-seller')
        @else
            @include('layouts.partials.nav-admin')
        @endif
    @endauth

    <main class="mx-auto w-full max-w-5xl px-4 pb-28 pt-5 sm:pb-10">
    @if (!request()->routeIs('student.activation'))
        @include('components.flash')
    @endif

    @yield('content')
</main>

    @auth
        @if (auth()->user()->isStudent())
            @include('layouts.partials.tabbar-student')
        @endif
    @endauth
</body>
</html>
