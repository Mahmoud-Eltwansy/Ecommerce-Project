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
        'active' => 'dashboard.categories.*',
        'ability' => 'view',
        'model' => App\Models\Category::class

    ],
    [
        'title' => 'Products',
        'route' => 'dashboard.products.index',
        'icon' => 'far fa-circle nav-icon',
        'active' => 'dashboard.products.*',
        'ability' => 'view',
        'model' => App\Models\Product::class
    ],
    [
        'title' => 'Orders',
        'route' => 'dashboard.orders.index',
        'icon' => 'far fa-circle nav-icon',
        'active' => 'dashboard.orders.*',
        'ability' => 'view',
        'model' => App\Models\Order::class
    ],
    [
        'title' => 'Roles',
        'route' => 'dashboard.roles.index',
        'icon' => 'far fa-circle nav-icon',
        'active' => 'dashboard.roles.*',
        'ability' => 'view',
        'model' => App\Models\Role::class
    ],
    [
        'title' => 'Users',
        'route' => 'dashboard.users.index',
        'icon' => 'fas fa-users nav-icon',
        'active' => 'dashboard.users.*',
        'ability' => 'view',
        'model' => App\Models\User::class
    ],
    [
        'title' => 'Admins',
        'route' => 'dashboard.admins.index',
        'icon' => 'fas fa-users nav-icon',
        'active' => 'dashboard.admins.*',
        'ability' => 'view',
        'model' => App\Models\Admin::class
    ],


];
