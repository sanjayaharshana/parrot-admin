# FormService Routing Guide

This guide explains the new user-friendly routing system that binds routes directly to the FormService.

## Overview

The FormService now includes built-in routing methods that automatically set the correct form action and method, making it much more intuitive and user-friendly to work with CRUD forms.

## New FormService Routing Methods

### 1. `routeForStore()` - For Create Forms
```php
// Automatically sets POST method and store route
$this->form->routeForStore('products');
// Results in: action="/products" method="POST"
```

### 2. `routeForUpdate()` - For Edit Forms
```php
// Automatically sets PUT method and update route
$this->form->routeForUpdate('products', $id);
// Results in: action="/products/123" method="PUT"
```

### 3. `routeFor()` - For Any Action
```php
// Generic method for any route action
$this->form->routeFor('products', 'store');           // POST /products
$this->form->routeFor('products', 'update', $id);     // PUT /products/123
$this->form->routeFor('products', 'destroy', $id);    // DELETE /products/123
```

## Implementation Example

### 1. Controller Setup

```php
<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\BaseController;
use Modules\UserPanel\Services\DataViewService;

class ProductController extends BaseController
{
    public $icon = 'fa fa-box';
    public $showInSidebar = true;
    public $model = Product::class;

    public $routeName = 'products';

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        // Set the form route for store action
        $this->form->routeForStore($this->getRouteName());
        
        $this->createForm();
        return view('userpanel::create', [
            'form' => $this->form
        ]);
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        
        // Bind the model to the form service
        $this->form->bindModel($product);
        
        // Set the form route for update action
        $this->form->routeForUpdate($this->getRouteName(), $id);
        
        $this->createForm();
        
        return view('userpanel::edit', [
            'form' => $this->form,
            'product' => $product,
            'title' => 'Edit Product'
        ]);
    }

    /**
     * Create form layout for creating/editing products
     */
    public function createForm()
    {
        $layout = $this->layoutService;
        $layout->setFormService($this->form);
        
        // Define your form fields here...
        $row = $layout->row();
        $row->column(6, function ($form, $column) {
            $column->addField(
                $form->text()
                    ->name('name')
                    ->label('Product Name')
                    ->required()
            );
        });
        
        return $layout->render();
    }

    // Other CRUD methods...
}
```

### 2. Blade Templates

The Blade templates are now much simpler and use the FormService directly:

```blade
{{-- create.blade.php --}}
@extends('userpanel::components.layouts.master')

@section('content')
    <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
        <form method="{{ $form->getMethod() }}" action="{{ $form->getAction() }}" enctype="multipart/form-data" class="space-y-6">
            {!! $form->renderFormContent() !!}
            
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ url()->previous() }}" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Create
                </button>
            </div>
        </form>
    </div>
@endsection
```

```blade
{{-- edit.blade.php --}}
@extends('userpanel::components.layouts.master')

@section('content')
    <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
        <form method="{{ $form->getMethod() }}" action="{{ $form->getAction() }}" enctype="multipart/form-data" class="space-y-6">
            {!! $form->renderFormContent() !!}
            
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ url()->previous() }}" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Update
                </button>
            </div>
        </form>
    </div>
@endsection
```

## FormService Methods

### Routing Methods
```php
// Set route for store action
$form->routeForStore('products');

// Set route for update action
$form->routeForUpdate('products', $id);

// Set route for any action
$form->routeFor('products', 'store');
$form->routeFor('products', 'update', $id);
$form->routeFor('products', 'destroy', $id);
```

### Form Rendering Methods
```php
// Render complete form with wrapper
$form->renderForm();

// Render form content only (for custom wrappers)
$form->renderFormContent();

// Get form action and method
$form->getAction();    // Returns the action URL
$form->getMethod();    // Returns the HTTP method
```

### Traditional Methods (Still Available)
```php
// Manual route setting
$form->action(route('products.store'));
$form->method('POST');

// Form attributes
$form->formAttribute('enctype', 'multipart/form-data');
```

## Benefits

### 1. **User-Friendly API**
- Simple, intuitive method names
- Automatic HTTP method detection
- No need to remember route patterns

### 2. **Automatic Method Detection**
- `routeForStore()` → POST method
- `routeForUpdate()` → PUT method
- `routeFor('destroy')` → DELETE method

### 3. **Cleaner Controllers**
- Less boilerplate code
- Focus on business logic
- Consistent patterns

### 4. **Simplified Templates**
- No need to pass route information
- FormService handles all routing
- Cleaner Blade templates

### 5. **Flexible and Extensible**
- Easy to add new routing methods
- Supports custom route patterns
- Backward compatible

## Advanced Usage

### Custom Route Patterns
```php
class CustomController extends BaseController
{
    public $routeName = 'custom-pattern';

    public function create()
    {
        // Use custom route name
        $this->form->routeForStore($this->getRouteName());
        
        // Or use specific route
        $this->form->routeFor('custom-pattern', 'store');
        
        $this->createForm();
        return view('userpanel::create', ['form' => $this->form]);
    }
}
```

### Multiple Resource Types
```php
class MultiResourceController extends BaseController
{
    protected $resourceType = 'products';

    public function create($resourceType = null)
    {
        $routeName = $resourceType ?: $this->resourceType;
        $this->form->routeForStore($routeName);
        
        $this->createForm();
        return view('userpanel::create', ['form' => $this->form]);
    }
}
```

### Custom Form Attributes
```php
public function create()
{
    $this->form->routeForStore('products');
    
    // Add custom form attributes
    $this->form->formAttribute('enctype', 'multipart/form-data');
    $this->form->formAttribute('data-ajax', 'true');
    
    $this->createForm();
    return view('userpanel::create', ['form' => $this->form]);
}
```

## Best Practices

1. **Use the New Methods**: Prefer `routeForStore()` and `routeForUpdate()` over manual route setting
2. **Keep It Simple**: Let the FormService handle routing logic
3. **Consistent Patterns**: Use the same approach across all controllers
4. **Set Route Names**: Use the `$routeName` property for custom route names
5. **Test Routes**: Always verify that the generated routes match your expectations

## Migration from Old System

### Before (Old System)
```php
// Controller
public function create()
{
    $layout = $this->createForm();
    return view('userpanel::create', [
        'layout' => $layout,
        'formAction' => $this->getStoreRoute(),
        'formMethod' => 'POST'
    ]);
}

// Blade Template
<form method="{{ $formMethod }}" action="{{ $formAction }}" ...>
    @csrf
    {!! $layout !!}
</form>
```

### After (New System)
```php
// Controller
public function create()
{
    $this->form->routeForStore($this->getRouteName());
    $this->createForm();
    return view('userpanel::create', ['form' => $this->form]);
}

// Blade Template
<form method="{{ $form->getMethod() }}" action="{{ $form->getAction() }}" ...>
    {!! $form->renderFormContent() !!}
</form>
```

This new system provides a much more intuitive and maintainable way to handle form routing in your CRUD applications! 