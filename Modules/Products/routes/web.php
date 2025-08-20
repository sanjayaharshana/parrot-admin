<?php

use Illuminate\Support\Facades\Route;
use Modules\Products\Http\Controllers\BrandsController;
use Modules\Products\Http\Controllers\CategoriesController;


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
