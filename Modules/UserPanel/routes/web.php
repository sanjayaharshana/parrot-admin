<?php

use Illuminate\Support\Facades\Route;
use Modules\UserPanel\Http\Controllers\UserPanelController;
use Modules\UserPanel\Http\Controllers\CallbackLayoutController;
use Modules\UserPanel\Http\Controllers\CallableOptionsController;
use Modules\UserPanel\Http\Controllers\CustomContentController;
use Modules\UserPanel\Http\Controllers\ModelBindingController;

Route::middleware(['auth', 'verified'])->group(function () {
    // Layout Service Demo
    Route::resource('/dashboard', \Modules\UserPanel\Http\Controllers\DashboardController::class);

    // Callback Layout Examples
    Route::get('/callback-layout', [CallbackLayoutController::class, 'index'])->name('userpanel.callback-layout');
    Route::get('/callback-layout/advanced', [CallbackLayoutController::class, 'advanced'])->name('userpanel.callback-layout.advanced');

    // Callable Options Examples
    Route::get('/callable-options', [CallableOptionsController::class, 'index'])->name('userpanel.callable-options');
    Route::get('/callable-options/advanced', [CallableOptionsController::class, 'advanced'])->name('userpanel.callable-options.advanced');

    // Custom Content Examples
    Route::get('/custom-content', [CustomContentController::class, 'index'])->name('userpanel.custom-content');
    Route::get('/custom-content/advanced', [CustomContentController::class, 'advanced'])->name('userpanel.custom-content.advanced');

    // Model Binding Examples
    Route::get('/model-binding', [ModelBindingController::class, 'index'])->name('userpanel.model-binding');
    Route::get('/model-binding/advanced', [ModelBindingController::class, 'advanced'])->name('userpanel.model-binding.advanced');
    Route::get('/model-binding/{id}/edit', [ModelBindingController::class, 'edit'])->name('userpanel.model-binding.edit');
    Route::post('/model-binding', [ModelBindingController::class, 'store'])->name('userpanel.model-binding.store');
    Route::put('/model-binding/{id}', [ModelBindingController::class, 'update'])->name('userpanel.model-binding.update');
});
