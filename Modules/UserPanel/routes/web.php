<?php

use Illuminate\Support\Facades\Route;
use Modules\UserPanel\Http\Controllers\UserPanelController;
use Modules\UserPanel\Http\Controllers\CallbackLayoutController;
use Modules\UserPanel\Http\Controllers\CallableOptionsController;
use Modules\UserPanel\Http\Controllers\CustomContentController;

Route::middleware(['auth', 'verified'])->group(function () {
    // Layout Service Demo
    Route::get('/dashboard', [\Modules\UserPanel\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');

    // Callback Layout Examples
    Route::get('/callback-layout', [CallbackLayoutController::class, 'index'])->name('userpanel.callback-layout');
    Route::get('/callback-layout/advanced', [CallbackLayoutController::class, 'advanced'])->name('userpanel.callback-layout.advanced');

    // Callable Options Examples
    Route::get('/callable-options', [CallableOptionsController::class, 'index'])->name('userpanel.callable-options');
    Route::get('/callable-options/advanced', [CallableOptionsController::class, 'advanced'])->name('userpanel.callable-options.advanced');

    // Custom Content Examples
    Route::get('/custom-content', [CustomContentController::class, 'index'])->name('userpanel.custom-content');
    Route::get('/custom-content/advanced', [CustomContentController::class, 'advanced'])->name('userpanel.custom-content.advanced');
});
