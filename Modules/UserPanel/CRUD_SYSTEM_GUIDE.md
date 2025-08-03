# UserPanel CRUD System Guide

This guide explains how the CRUD (Create, Read, Update, Delete) system works in the UserPanel module, using the `DashboardController` and `ProductController` as examples.

## Overview

The UserPanel module provides a complete CRUD system that extends `BaseController` and uses three main services:
- **LayoutService**: For creating responsive form layouts
- **FormService**: For creating form fields and handling form data
- **DataViewService**: For creating data grids with sorting, filtering, and pagination

## System Architecture

### 1. BaseController Structure

```php
class BaseController extends Controller
{
    public $icon = 'fa fa-users';           // FontAwesome icon for sidebar
    public $showInSidebar = true;           // Show in sidebar navigation
    protected $layoutService;               // Layout service instance
    protected $form;                        // Form service instance
    
    function __construct()
    {
        $this->layoutService = new LayoutService();
        $this->form = new FormService();
    }
    
    // Standard CRUD methods (index, create, store, show, edit, update, destroy)
}
```

### 2. Required Methods for CRUD

#### `createForm()` - Form Layout Creation
This method creates the form layout for creating and editing records:

```php
public function createForm()
{
    $layout = $this->layoutService;
    $layout->setFormService($this->form);
    
    // Create rows and columns
    $row = $layout->row();
    $row->column(6, function ($form, $column) {
        $column->addField(
            $form->text()
                ->name('name')
                ->label('Name')
                ->required()
        );
    });
    
    return $layout->render();
}
```

#### `dataSetView()` - Data Grid Creation
This method creates the data grid for listing records:

```php
public function dataSetView()
{
    $grid = new DataViewService(new YourModel());
    
    // Define columns
    $grid->id('ID')->sortable();
    $grid->column('name', 'Name')->sortable();
    $grid->column('status', 'Status')->display(function($value) {
        return $value ? 'Active' : 'Inactive';
    });
    
    // Add filters
    $grid->addTextFilter('name', 'Name');
    $grid->addFilter('status', 'Status', ['1' => 'Active', '0' => 'Inactive']);
    
    // Configure settings
    $grid->perPage(15)
        ->defaultSort('created_at', 'desc')
        ->search(true)
        ->pagination(true);
    
    return $grid->render();
}
```

## Complete CRUD Controller Example

Here's how to create a complete CRUD controller:

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
    
    // Required methods...
}
```

### 2. Form Creation (`createForm()`)

```php
public function createForm()
{
    $layout = $this->layoutService;
    $layout->setFormService($this->form);
    
    // First row
    $row1 = $layout->row();
    $row1->column(6, function ($form, $column) {
        $column->addField(
            $form->text()
                ->name('name')
                ->label('Name')
                ->placeholder('Enter name')
                ->required()
        );
    });
    $row1->column(6, function ($form, $column) {
        $column->addField(
            $form->email()
                ->name('email')
                ->label('Email')
                ->required()
        );
    });
    
    // Second row
    $row2 = $layout->row();
    $row2->column(12, function ($form, $column) {
        $column->addField(
            $form->textarea()
                ->name('description')
                ->label('Description')
                ->required()
        );
    });
    
    return $layout->render();
}
```

### 3. Data Grid Creation (`dataSetView()`)

```php
public function dataSetView()
{
    $grid = new DataViewService(new YourModel());
    
    $grid->title('Your Resource Management');
    $grid->description('Manage your resources efficiently');
    
    // Define columns
    $grid->id('ID')->sortable();
    $grid->column('name', 'Name')->sortable();
    $grid->column('email', 'Email')->sortable();
    $grid->column('status', 'Status')->display(function($value) {
        return $value ? 
            '<span class="badge badge-success">Active</span>' : 
            '<span class="badge badge-danger">Inactive</span>';
    });
    $grid->column('created_at', 'Created')->display(function($value) {
        return date('M d, Y', strtotime($value));
    });
    
    // Add filters
    $grid->addTextFilter('name', 'Name');
    $grid->addTextFilter('email', 'Email');
    $grid->addFilter('status', 'Status', ['1' => 'Active', '0' => 'Inactive']);
    
    // Add create button
    $grid->createButton(url('your-resource/create'), 'Add New', 'fa fa-plus');
    
    // Configure grid
    $grid->perPage(15)
        ->defaultSort('created_at', 'desc')
        ->search(true)
        ->pagination(true);
    
    return $grid->render();
}
```

### 4. CRUD Operations

#### Store (Create)
```php
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:your_table,email',
        'description' => 'required|string'
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    try {
        $model = new YourModel();
        $model->name = $request->name;
        $model->email = $request->email;
        $model->description = $request->description;
        $model->save();

        return redirect()->route('your-resource.index')
            ->with('success', 'Record created successfully!');
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Error creating record: ' . $e->getMessage());
    }
}
```

#### Update
```php
public function update(Request $request, $id)
{
    $model = YourModel::findOrFail($id);
    
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:your_table,email,' . $id,
        'description' => 'required|string'
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    try {
        $model->name = $request->name;
        $model->email = $request->email;
        $model->description = $request->description;
        $model->save();

        return redirect()->route('your-resource.index')
            ->with('success', 'Record updated successfully!');
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Error updating record: ' . $e->getMessage());
    }
}
```

#### Delete
```php
public function destroy($id)
{
    try {
        $model = YourModel::findOrFail($id);
        $model->delete();

        return redirect()->route('your-resource.index')
            ->with('success', 'Record deleted successfully!');
    } catch (\Exception $e) {
        return redirect()->route('your-resource.index')
            ->with('error', 'Error deleting record: ' . $e->getMessage());
    }
}
```

### 5. Route Registration

```php
// In Modules/UserPanel/routes/web.php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/your-resource', \Modules\UserPanel\Http\Controllers\YourController::class);
    Route::post('/your-resource/bulk-action', [\Modules\UserPanel\Http\Controllers\YourController::class, 'bulkAction'])
        ->name('userpanel.your-resource.bulk-action');
});
```

## Available Form Field Types

### Text Fields
```php
$form->text()->name('field_name')->label('Label')->required()
$form->email()->name('email')->label('Email')->required()
$form->password()->name('password')->label('Password')->required()
$form->number()->name('quantity')->label('Quantity')->step('0.01')->required()
```

### Text Areas
```php
$form->textarea()->name('description')->label('Description')->required()
```

### Select Fields
```php
$form->select()
    ->name('category')
    ->label('Category')
    ->options([
        'option1' => 'Option 1',
        'option2' => 'Option 2'
    ])
    ->required()
```

### File Uploads
```php
$form->file()
    ->name('image')
    ->label('Image')
    ->accept('image/*')
```

### Checkboxes and Radio Buttons
```php
$form->checkbox()->name('is_active')->label('Active')
$form->radio()->name('status')->label('Status')->options(['active' => 'Active', 'inactive' => 'Inactive'])
```

## Data Grid Features

### Column Types
```php
// Basic column
$grid->column('name', 'Name')->sortable();

// Column with custom display
$grid->column('status', 'Status')->display(function($value) {
    return $value ? 'Active' : 'Inactive';
});

// ID column (automatically sortable)
$grid->id('ID')->sortable();
```

### Filters
```php
// Text filter
$grid->addTextFilter('name', 'Name');

// Select filter
$grid->addFilter('status', 'Status', ['1' => 'Active', '0' => 'Inactive'], 'select');

// Date range filter
$grid->addDateRangeFilter('created_at', 'Created Date');
```

### Grid Configuration
```php
$grid->perPage(15)                    // Items per page
    ->defaultSort('created_at', 'desc') // Default sorting
    ->search(true)                     // Enable search
    ->pagination(true)                 // Enable pagination
    ->title('Your Title')              // Set title
    ->description('Your description'); // Set description
```

## Layout System

### Rows and Columns
```php
$row = $layout->row();
$row->column(6, function ($form, $column) {
    // 6-column width (half page)
    $column->addField($form->text()->name('field1'));
});
$row->column(6, function ($form, $column) {
    // Another 6-column width
    $column->addField($form->text()->name('field2'));
});
```

### Custom HTML
```php
$column->addHtml('<p class="text-muted">Custom HTML content</p>');
```

### Custom Views
```php
$column->addView('userpanel::components.custom-stats', [
    'stats' => $statsData
]);
```

## Best Practices

1. **Model Integration**: Always define your `$model` property
2. **Validation**: Use Laravel's validation in store/update methods
3. **Error Handling**: Use try-catch blocks for database operations
4. **File Handling**: Implement proper file upload and deletion
5. **User Feedback**: Use flash messages for success/error states
6. **Security**: Implement proper authorization checks
7. **Performance**: Use eager loading for relationships

## Example: Product Management

See `ProductController.php` for a complete example that includes:
- ✅ Form creation with multiple columns and fields
- ✅ Data grid with custom displays and actions
- ✅ File upload handling
- ✅ Validation
- ✅ Bulk actions
- ✅ Custom styling and icons
- ✅ Status indicators
- ✅ Search and pagination

This CRUD system provides a consistent, maintainable way to create admin interfaces similar to Filament, Nova, or other admin panels. 