# Resource Controller Pattern Documentation

This document explains how to create Resource Route controllers that follow the same pattern as `DashboardController` and work seamlessly with the UserPanel module.

## Overview

The pattern is based on extending `BaseController` which provides:
- Layout service for creating forms
- Form service for form fields
- Data view service for data grids
- Standard CRUD operations
- Sidebar integration

## Structure

### 1. Controller Structure

```php
<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\YourModel;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\BaseController;
use Modules\UserPanel\Services\DataViewService;

class YourController extends BaseController
{
    public $icon = 'fa fa-icon'; // FontAwesome icon for sidebar
    public $showInSidebar = true; // Show in sidebar navigation
    public $model = YourModel::class; // Your Eloquent model

    /**
     * Create form layout for creating/editing
     */
    public function createForm()
    {
        $layout = $this->layoutService;
        $layout->setFormService($this->form);
        
        // Define your form layout here
        // Use $layout->row() and $column() methods
        
        return $layout->render();
    }

    /**
     * Data grid view for listing
     */
    public function dataSetView()
    {
        $grid = new DataViewService(new YourModel());
        
        // Define your grid columns here
        // Use $grid->column() method
        
        return $grid->render();
    }

    // Standard CRUD methods (store, show, edit, update, destroy)
    // Bulk actions method
}
```

### 2. Required Methods

#### `createForm()`
- Creates the form layout for create/edit operations
- Uses `LayoutService` and `FormService`
- Returns rendered form HTML

#### `dataSetView()`
- Creates the data grid for listing records
- Uses `DataViewService`
- Returns rendered grid HTML

#### Standard CRUD Methods
- `store(Request $request)` - Create new record
- `show($id)` - Display single record
- `edit($id)` - Show edit form
- `update(Request $request, $id)` - Update record
- `destroy($id)` - Delete record
- `bulkAction(Request $request)` - Handle bulk operations

### 3. Route Registration

```php
// In Modules/UserPanel/routes/web.php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/your-resource', \Modules\UserPanel\Http\Controllers\YourController::class);
    Route::post('/your-resource/bulk-action', [\Modules\UserPanel\Http\Controllers\YourController::class, 'bulkAction'])
        ->name('userpanel.your-resource.bulk-action');
});
```

## Example Implementation

See `ProductController.php` for a complete example that includes:

- ✅ Form creation with multiple columns and fields
- ✅ Data grid with custom displays and actions
- ✅ File upload handling
- ✅ Validation
- ✅ Bulk actions
- ✅ Custom styling and icons
- ✅ Status indicators
- ✅ Search and pagination

## Key Features

### Layout Service
- Create responsive form layouts
- Add custom HTML content
- Support for multiple columns
- Field grouping and organization

### Form Service
- Text inputs, textareas, selects
- File uploads
- Number inputs
- Validation integration
- Custom field types

### Data View Service
- Sortable columns
- Custom displays with HTML
- Action buttons
- Bulk operations
- Search and filtering
- Pagination

### Sidebar Integration
- Automatic menu generation
- Icon support
- Show/hide control
- Route-based navigation

## Best Practices

1. **Model Integration**: Always define your `$model` property
2. **Validation**: Use Laravel's validation in store/update methods
3. **File Handling**: Implement proper file upload and deletion
4. **Error Handling**: Use try-catch blocks for database operations
5. **User Feedback**: Use flash messages for success/error states
6. **Security**: Implement proper authorization checks
7. **Performance**: Use eager loading for relationships

## Migration to Your Own Model

To use this pattern with your own model:

1. Create your Eloquent model
2. Create database migration
3. Update controller to use your model
4. Adjust form fields to match your model attributes
5. Update validation rules
6. Test all CRUD operations

## Available Services

- `LayoutService`: Form layout management
- `FormService`: Form field creation
- `DataViewService`: Data grid management
- `PluginService`: Plugin integration

This pattern provides a consistent, maintainable way to create admin interfaces similar to Filament, Nova, or other admin panels. 