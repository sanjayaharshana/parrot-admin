# CRUD Routing System Guide

This guide explains how the backend routing system works for CRUD forms in the UserPanel module.

## Overview

The routing system automatically determines the correct form action and method based on the controller context, eliminating the need to hardcode routes in Blade templates.

## How It Works

### 1. BaseController Routing Methods

The `BaseController` provides these methods for automatic route generation:

```php
/**
 * Get the store route for the current resource
 */
protected function getStoreRoute()
{
    $routeName = $this->getRouteName() . '.store';
    return route($routeName);
}

/**
 * Get the update route for the current resource
 */
protected function getUpdateRoute($id)
{
    $routeName = $this->getRouteName() . '.update';
    return route($routeName, $id);
}

/**
 * Get the route name prefix for the current resource
 * Override this in child controllers if needed
 */
protected function getRouteName()
{
    // Extract route name from the current controller class name
    $className = class_basename($this);
    $resourceName = str_replace('Controller', '', $className);
    return strtolower($resourceName) . 's';
}
```

### 2. Automatic Route Name Detection

The system automatically detects route names based on controller class names:

| Controller Class | Auto-Detected Route Name | Store Route | Update Route |
|------------------|-------------------------|-------------|--------------|
| `ProductController` | `products` | `products.store` | `products.update` |
| `UserController` | `users` | `users.store` | `users.update` |
| `CategoryController` | `categorys` | `categorys.store` | `categorys.update` |

### 3. Override Route Names

If you need a custom route name, override the `getRouteName()` method:

```php
class ProductController extends BaseController
{
    protected function getRouteName()
    {
        return 'products'; // Custom route name
    }
}
```

## Implementation Example

### 1. Controller Setup

```php
<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Models\YourModel;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\BaseController;
use Modules\UserPanel\Services\DataViewService;

class YourController extends BaseController
{
    public $icon = 'fa fa-icon';
    public $showInSidebar = true;
    public $model = YourModel::class;

    /**
     * Override route name if needed
     */
    protected function getRouteName()
    {
        return 'your-resource'; // Custom route name
    }

    /**
     * Show the form for creating a new resource
     */
    public function create()
    {
        $layout = $this->createForm();
        return view('userpanel::create', [
            'layout' => $layout,
            'formAction' => $this->getStoreRoute(),    // Automatically generated
            'formMethod' => 'POST'
        ]);
    }

    /**
     * Show the form for editing the specified resource
     */
    public function edit($id)
    {
        $model = YourModel::findOrFail($id);
        $this->form->bindModel($model);
        
        $layout = $this->createForm();
        return view('userpanel::edit', [
            'layout' => $layout,
            'model' => $model,
            'formAction' => $this->getUpdateRoute($id), // Automatically generated
            'formMethod' => 'PUT'
        ]);
    }

    // Other CRUD methods...
}
```

### 2. Blade Templates

The Blade templates use the backend-provided form action and method:

```blade
{{-- create.blade.php --}}
@extends('userpanel::components.layouts.master')

@section('content')
    <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
        <form method="{{ $formMethod }}" action="{{ $formAction }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            {!! $layout !!}
            
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
        <form method="{{ $formMethod }}" action="{{ $formAction }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            {!! $layout !!}
            
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

### 3. Route Registration

```php
// In Modules/UserPanel/routes/web.php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/your-resource', \Modules\UserPanel\Http\Controllers\YourController::class);
    Route::post('/your-resource/bulk-action', [\Modules\UserPanel\Http\Controllers\YourController::class, 'bulkAction'])
        ->name('userpanel.your-resource.bulk-action');
});
```

## Benefits

### 1. **Backend-Driven Routing**
- Routes are determined by the controller, not hardcoded in templates
- Easy to change routes without touching Blade files
- Consistent routing across all CRUD operations

### 2. **Automatic Route Generation**
- No need to manually specify route names
- Automatic detection based on controller class names
- Easy to override for custom route names

### 3. **Maintainable Code**
- Single source of truth for routing logic
- Easy to refactor and update
- Consistent patterns across all controllers

### 4. **Flexible and Extensible**
- Easy to add new routing methods
- Can be extended for different HTTP methods
- Supports custom route patterns

## Advanced Usage

### Custom Route Patterns

You can extend the routing system for custom patterns:

```php
class CustomController extends BaseController
{
    protected function getRouteName()
    {
        return 'custom-pattern';
    }

    /**
     * Custom route for specific actions
     */
    protected function getCustomRoute($action, $id = null)
    {
        $routeName = $this->getRouteName() . '.' . $action;
        return $id ? route($routeName, $id) : route($routeName);
    }
}
```

### Multiple Resource Types

For controllers that handle multiple resource types:

```php
class MultiResourceController extends BaseController
{
    protected $resourceType = 'products';

    protected function getRouteName()
    {
        return $this->resourceType;
    }

    public function setResourceType($type)
    {
        $this->resourceType = $type;
        return $this;
    }
}
```

## Best Practices

1. **Use the Base Methods**: Always use `getStoreRoute()` and `getUpdateRoute()` instead of hardcoding routes
2. **Override When Needed**: Only override `getRouteName()` when you need custom route names
3. **Keep It Simple**: Let the automatic detection work for standard naming conventions
4. **Consistent Patterns**: Use the same pattern across all your CRUD controllers
5. **Test Routes**: Always test that the generated routes match your route definitions

This system provides a clean, maintainable way to handle CRUD routing without hardcoding routes in your templates. 