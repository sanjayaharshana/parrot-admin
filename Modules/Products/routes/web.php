<?php

use Illuminate\Support\Facades\Route;
use Modules\Products\Http\Controllers\ProductsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/products', ProductsController::class)->names([ 'index'=>'products.index','create'=>'products.create','store'=>'products.store','show'=>'products.show',
        'edit'=>'products.edit','update'=>'products.update','destroy'=>'products.destroy']);
});
