<?php

namespace App\Providers;

use App\Support\Cart;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Memakai tampilan pagination sendiri agar kelas Tailwind-nya ikut ter-build.
        Paginator::defaultView('vendor.pagination.simple');
        Paginator::defaultSimpleView('vendor.pagination.simple');

        // Jumlah item keranjang dibagikan ke navbar siswa.
        View::composer('layouts.partials.nav-student', function ($view) {
            $view->with('cartCount', Cart::totalQuantity());
        });
    }
}
