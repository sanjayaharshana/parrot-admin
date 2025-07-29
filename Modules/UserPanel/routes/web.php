<?php

use Illuminate\Support\Facades\Route;
use Modules\UserPanel\Http\Controllers\UserPanelController;

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard routes
    Route::resource('/dashboard', UserPanelController::class);

    Route::resource('/test', \Modules\UserPanel\Http\Controllers\TestController::class);


//    Route::resource('plugin', )
});
