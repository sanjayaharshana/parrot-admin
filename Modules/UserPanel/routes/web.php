<?php

use Illuminate\Support\Facades\Route;
use Modules\UserPanel\Http\Controllers\UserPanelController;

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard routes
    Route::resource('/dashboard', UserPanelController::class);

    Route::resource('/test', \Modules\UserPanel\Http\Controllers\TestController::class);

    // Layout Service Demo
    Route::get('/layout-demo', [\Modules\UserPanel\Http\Controllers\LayoutDemoController::class, 'index'])->name('userpanel.layout-demo');
    Route::get('/layout-demo/alternative', [\Modules\UserPanel\Http\Controllers\LayoutDemoController::class, 'alternative'])->name('userpanel.layout-demo.alternative');

//    Route::resource('plugin', )
});
