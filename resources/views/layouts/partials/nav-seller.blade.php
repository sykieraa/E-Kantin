@php
    // 'match' menentukan kapan menu dianggap aktif (termasuk halaman turunannya).
    $links = [
    ['route' => 'seller.dashboard', 'label' => 'Dashboard', 'match' => 'seller.dashboard'],
    ['route' => 'seller.orders.index', 'label' => 'Pesanan', 'match' => 'seller.orders.*'],
    ['route' => 'seller.products.index', 'label' => 'Menu', 'match' => 'seller.products.*'],
    ['route' => 'seller.canteen.status', 'label' => 'Status kantin', 'match' => 'seller.canteen.*'],
    ['route' => 'seller.history.index', 'label' => 'Riwayat', 'match' => 'seller.history.*'],
    ];
@endphp
@include('layouts.partials.nav-bar', ['links' => $links, 'home' => route('seller.dashboard')])
