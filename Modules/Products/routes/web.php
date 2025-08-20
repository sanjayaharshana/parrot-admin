<?php

use Illuminate\Support\Facades\Route;
use Modules\Products\Http\Controllers\BrandsController;
use Modules\Products\Http\Controllers\CategoriesController;
use Modules\Products\Http\Controllers\DigitalProductsController;


    Route::resource('/brands', BrandsController::class)
        ->names([
            'index'=>'brands.index',
            'create'=>'brands.create',
            'store'=>'brands.store',
            'show'=>'brands.show',
            'edit'=>'brands.edit',
            'update'=>'brands.update',
            'destroy'=>'brands.destroy'
        ]);


    Route::resource('/categories', CategoriesController::class)
        ->names([
            'index'=>'categories.index',
            'create'=>'categories.create',
            'store'=>'categories.store',
            'show'=>'categories.show',
            'edit'=>'categories.edit',
            'update'=>'categories.update',
            'destroy'=>'categories.destroy'
        ]);

    Route::resource('/digital-products', DigitalProductsController::class)
        ->names([
            'index'=>'digital-products.index',
            'create'=>'digital-products.create',
            'store'=>'digital-products.store',
            'show'=>'digital-products.show',
            'edit'=>'digital-products.edit',
            'update'=>'digital-products.update',
            'destroy'=>'digital-products.destroy'
        ]);
