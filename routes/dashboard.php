<?php

use App\Http\Controllers\Dashboard\CategoriesController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ProductsController;
use App\Http\Controllers\Dashboard\ProfileController;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => ['auth:admin'],
    'as' => 'dashboard.',
    'prefix' => 'admin/dashboard'
], function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');

    // Categories
    Route::get('categories/trash', [CategoriesController::class, 'trash'])->name('categories.trash');
    Route::put('categories/{category}/restore', [CategoriesController::class, 'restore'])->name('categories.restore');
    Route::delete('categories/{category}/force-delete', [CategoriesController::class, 'forceDelete'])->name('categories.force-delete');
    Route::resource('categories', CategoriesController::class);

    // Products
    Route::get('products/trash', [ProductsController::class, 'trash'])->name('products.trash');
    Route::put('products/{product}/restore', [ProductsController::class, 'restore'])->name('products.restore');
    Route::delete('products/{product}/force-delete', [ProductsController::class, 'forceDelete'])->name('products.force-delete');
    Route::resource('products', ProductsController::class);
});
