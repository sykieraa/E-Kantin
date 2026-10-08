@php
    $links = [
        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'match' => 'admin.dashboard'],
        ['route' => 'admin.students.index', 'label' => 'Siswa', 'match' => 'admin.students.*'],
        ['route' => 'admin.sellers.index', 'label' => 'Penjual & Kantin', 'match' => 'admin.sellers.*'],
        ['route' => 'admin.categories.index', 'label' => 'Kategori', 'match' => 'admin.categories.*'],
        ['route' => 'admin.products.index', 'label' => 'Menu', 'match' => 'admin.products.*'],
        ['route' => 'admin.orders.index', 'label' => 'Pesanan', 'match' => 'admin.orders.*'],
    ];
@endphp
@include('layouts.partials.nav-bar', ['links' => $links, 'home' => route('admin.dashboard')])
