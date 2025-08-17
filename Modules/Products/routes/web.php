<?php

use Illuminate\Support\Facades\Route;
use Modules\Products\Http\Controllers\ProductsController;
use Modules\Products\Http\Controllers\BrandsController;
use Modules\Products\Http\Controllers\CategoriesController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/products', ProductsController::class)->names([ 'index'=>'products.index','create'=>'products.create','store'=>'products.store','show'=>'products.show',
        'edit'=>'products.edit','update'=>'products.update','destroy'=>'products.destroy']);

    Route::resource('/brands', BrandsController::class)->names([ 'index'=>'brands.index','create'=>'brands.create','store'=>'brands.store','show'=>'brands.show','edit'=>'bra
nds.edit','update'=>'brands.update','destroy'=>'brands.destroy']);


    Route::resource('/categories', CategoriesController::class)->names([ 'index'=>'categories.index','create'=>'categories.create','store'=>'categories.store','show'=>'categ
ories.show','edit'=>'categories.edit','update'=>'categories.update','destroy'=>'categories.destroy']);


});
