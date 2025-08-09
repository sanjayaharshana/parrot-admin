<?php

use Illuminate\Support\Facades\Route;
use Modules\Documentation\Http\Controllers\DocumentationController;
use Modules\Documentation\Http\Controllers\DocumentationPageController;

Route::prefix('documentation')->name('documentation.')->group(function () {
    Route::get('/', [DocumentationController::class, 'index'])->name('index');
    Route::get('/category/{slug}', [DocumentationController::class, 'category'])->name('category');
    Route::get('/{categorySlug}/{pageSlug}', [DocumentationController::class, 'page'])->name('page');

    // Admin routes (you might want to add middleware for admin access)
    Route::get('/create', [DocumentationController::class, 'create'])->name('create');
    Route::post('/', [DocumentationController::class, 'store'])->name('store');
    Route::get('/{id}', [DocumentationController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [DocumentationController::class, 'edit'])->name('edit');
    Route::put('/{id}', [DocumentationController::class, 'update'])->name('update');
    Route::delete('/{id}', [DocumentationController::class, 'destroy'])->name('destroy');

});


Route::resource('/documentation-pages', DocumentationPageController::class)->names([ 'index'=>'documentation-pages.index','create'=>'documentation-pages.create',
    'store'=>'documentation-pages.store','show'=>'documentation-pages.show','edit'=>'documentation-pages.edit','update'=>'documentation-pages.update','destroy'=>'documentation-pages.destroy']);


