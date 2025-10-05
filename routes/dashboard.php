<?php

use App\Http\Controllers\Dashboard\CategoriesController;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => ['auth', 'verified'],
    'as' => 'dashboard.',
    'prefix' => 'dashboard'
], function () {
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::resource('/categories', CategoriesController::class);
});
