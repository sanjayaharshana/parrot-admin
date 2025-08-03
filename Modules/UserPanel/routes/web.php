<?php

use Illuminate\Support\Facades\Route;
use Modules\UserPanel\Http\Controllers\UserPanelController;
use Modules\UserPanel\Http\Controllers\CallbackLayoutController;
use Modules\UserPanel\Http\Controllers\CallableOptionsController;
use Modules\UserPanel\Http\Controllers\CustomContentController;
use Modules\UserPanel\Http\Controllers\ModelBindingController;
use Modules\UserPanel\Http\Controllers\DataViewController;
use Modules\UserPanel\Http\Controllers\SimpleLayoutController;
use Modules\UserPanel\Http\Controllers\TestController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/dashboard', \Modules\UserPanel\Http\Controllers\DashboardController::class);
    Route::resource('/products', \Modules\UserPanel\Http\Controllers\ProductController::class);
    Route::post('/products/bulk-action', [\Modules\UserPanel\Http\Controllers\ProductController::class, 'bulkAction'])
        ->name('userpanel.products.bulk-action');
});
