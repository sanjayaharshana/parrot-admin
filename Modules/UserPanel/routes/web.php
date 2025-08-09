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
use Modules\UserPanel\Http\Controllers\MediaController;

Route::middleware(['auth', 'verified'])->group(function () {

    Route::resource('/t-v-shows', \Modules\UserPanel\Http\Controllers\TVShowsController::class)->names([ 'index'=>'t-v-shows.index','create'=>'t-v-shows.create','store'=>'t-v-shows.store','show'=>'t-v-shows.sh
ow','edit'=>'t-v-shows.edit','update'=>'t-v-shows.update','destroy'=>'t-v-shows.destroy']);



    // Media manager endpoints
    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::post('/media', [MediaController::class, 'store'])->name('media.store');
    Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
    Route::resource('/dashboard', \Modules\UserPanel\Http\Controllers\DashboardController::class)->names([
        'index' => 'dashboard.index',
        'create' => 'dashboard.create',
        'store' => 'dashboard.store',
        'show' => 'dashboard.show',
        'edit' => 'dashboard.edit',
        'update' => 'dashboard.update',
        'destroy' => 'dashboard.destroy',
    ]);

    Route::resource('/products', \Modules\UserPanel\Http\Controllers\ProductController::class)->names([
        'index' => 'products.index',
        'create' => 'products.create',
        'store' => 'products.store',
        'show' => 'products.show',
        'edit' => 'products.edit',
        'update' => 'products.update',
        'destroy' => 'products.destroy',
    ]);

    Route::resource('/ships', \Modules\UserPanel\Http\Controllers\ShipController::class)->names([
        'index' => 'ships.index',
        'create' => 'ships.create',
        'store' => 'ships.store',
        'show' => 'ships.show',
        'edit' => 'ships.edit',
        'update' => 'ships.update',
        'destroy' => 'ships.destroy',
    ]);

    Route::resource('/users', \Modules\UserPanel\Http\Controllers\UserResourceController::class)->names([
        'index' => 'users.index',
        'create' => 'users.create',
        'store' => 'users.store',
        'show' => 'users.show',
        'edit' => 'users.edit',
        'update' => 'users.update',
        'destroy' => 'users.destroy',
    ]);

    Route::post('/products/bulk-action', [\Modules\UserPanel\Http\Controllers\ProductController::class, 'bulkAction'])
        ->name('userpanel.products.bulk-action');
});

// Example Tab Controller Routes (for demonstration)
Route::prefix('example-tabs')->name('example-tabs.')->group(function () {
    Route::get('/', [\Modules\UserPanel\Http\Controllers\ExampleTabController::class, 'index'])->name('index');
    Route::get('/create', [\Modules\UserPanel\Http\Controllers\ExampleTabController::class, 'create'])->name('create');
    Route::post('/', [\Modules\UserPanel\Http\Controllers\ExampleTabController::class, 'store'])->name('store');
    Route::get('/{user}', [\Modules\UserPanel\Http\Controllers\ExampleTabController::class, 'show'])->name('show');
    Route::get('/{user}/edit', [\Modules\UserPanel\Http\Controllers\ExampleTabController::class, 'edit'])->name('edit');
    Route::put('/{user}', [\Modules\UserPanel\Http\Controllers\ExampleTabController::class, 'update'])->name('update');
    Route::delete('/{user}', [\Modules\UserPanel\Http\Controllers\ExampleTabController::class, 'destroy'])->name('destroy');
});
