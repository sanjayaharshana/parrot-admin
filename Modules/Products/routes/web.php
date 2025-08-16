<?php

use Illuminate\Support\Facades\Route;
use Modules\Products\Http\Controllers\ProductsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/products', ProductsController::class)
        ->names([
            'index'=>'products.index',
            'create'=>'products.create',
            'store'=>'products.store',
            'show'=>'products.show',
            'edit'=>'products.edit',
            'update'=>'products.update',
            'destroy'=>'products.destroy'
        ]);
    
    // Test route for WYSIWYG editor
    Route::get('/products/test-wysiwyg', function () {
        return view('products::test-wysiwyg');
    })->name('products.test-wysiwyg');
    
    // Public test route for WYSIWYG editor (no auth required)
    Route::get('/test-wysiwyg', function () {
        return view('products::test-wysiwyg');
    })->name('test-wysiwyg');
});

// Simple test route for debugging (no auth, no middleware)
Route::get('/debug-wysiwyg', function () {
    return view('products::test-wysiwyg');
})->name('debug-wysiwyg');

// Minimal test route
Route::get('/simple-test', function () {
    return view('products::simple-test');
})->name('simple-test');

// Debug route to see actual form HTML
Route::get('/debug-form', function () {
    $controller = new ProductsController();
    $resource = $controller->makeResource();
    
    // Create a simple form to test
    $form = new \Modules\UserPanel\Services\Form\FormService();
    $form->wysiwyg()
        ->name('description')
        ->label('Product Description')
        ->placeholder('Enter description');
    
    $html = $form->renderForm();
    
    return response($html)->header('Content-Type', 'text/html');
})->name('debug-form');

// Debug route to see what the WYSIWYG field renders
Route::get('/debug-wysiwyg-field', function () {
    $field = new \Modules\UserPanel\Services\Form\Field('wysiwyg');
    $field->name('description')
          ->label('Product Description')
          ->value('Test content');
    
    $html = $field->render();
    
    return response($html)->header('Content-Type', 'text/html');
})->name('debug-wysiwyg-field');

// Test the actual products create form
Route::get('/test-products-form', function () {
    $controller = new ProductsController();
    $result = $controller->create();
    
    // If it returns a view, render it
    if (is_string($result)) {
        return $result;
    }
    
    // If it returns an array with form data, show it
    if (is_array($result) && isset($result['form'])) {
        return response($result['form'])->header('Content-Type', 'text/html');
    }
    
    return 'Form result: ' . json_encode($result);
})->name('test-products-form');
