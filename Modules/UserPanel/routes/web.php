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
    // Layout Service Demo
    Route::resource('/dashboard', \Modules\UserPanel\Http\Controllers\DashboardController::class);

    // Products Resource Route (Example of complete CRUD controller)
    Route::resource('/products', \Modules\UserPanel\Http\Controllers\ProductController::class);
    Route::post('/products/bulk-action', [\Modules\UserPanel\Http\Controllers\ProductController::class, 'bulkAction'])->name('userpanel.products.bulk-action');

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

    // Data View Examples
    Route::get('/data-view', [DataViewController::class, 'index'])->name('userpanel.data-view');
    Route::get('/data-view/advanced', [DataViewController::class, 'advanced'])->name('userpanel.data-view.advanced');
    Route::get('/data-view/{id}', [DataViewController::class, 'show'])->name('userpanel.data-view.show');
    Route::get('/data-view/{id}/edit', [DataViewController::class, 'edit'])->name('userpanel.data-view.edit');
    Route::delete('/data-view/{id}', [DataViewController::class, 'destroy'])->name('userpanel.data-view.destroy');
    Route::post('/data-view/bulk-action', [DataViewController::class, 'bulkAction'])->name('userpanel.data-view.bulk-action');

    // Simple Layout Examples
    Route::get('/simple-layout', [SimpleLayoutController::class, 'index'])->name('userpanel.simple-layout');

    // Test Examples with Search & Filters
    Route::get('/test', [TestController::class, 'index'])->name('userpanel.test');
    Route::get('/test/debug', [TestController::class, 'debug'])->name('userpanel.test.debug');
    Route::get('/test/users-with-search', [TestController::class, 'usersWithSearch'])->name('userpanel.test.users-with-search');
    Route::get('/test/products-with-filters', [TestController::class, 'productsWithFilters'])->name('userpanel.test.products-with-filters');
    Route::get('/test/advanced-example', [TestController::class, 'advancedExample'])->name('userpanel.test.advanced-example');
    Route::get('/test/{id}', [TestController::class, 'show'])->name('userpanel.test.show');
    Route::get('/test/{id}/edit', [TestController::class, 'edit'])->name('userpanel.test.edit');
});
