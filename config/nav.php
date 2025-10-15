<?php

return [
    [
        'title' => 'Dashboard',
        'route' => 'dashboard.dashboard',
        'icon' => 'nav-icon fas fa-tachometer-alt',
        'active' => 'dashboard.dashboard'
    ],
    [
        'title' => 'Categories',
        'route' => 'dashboard.categories.index',
        'icon' => 'far fa-circle nav-icon',
        'badge' => 'New',
        'active' => 'dashboard.categories.*'

    ],
    [
        'title' => 'Products',
        'route' => 'dashboard.products.index',
        'icon' => 'far fa-circle nav-icon',
        'active' => 'dashboard.products.*'
    ],
    [
        'title' => 'Orders',
        'route' => 'dashboard.categories.index',
        'icon' => 'far fa-circle nav-icon',
        'active' => 'dashboard.orders.*'
    ],



];
