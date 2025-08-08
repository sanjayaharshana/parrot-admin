# ResourceService Documentation

## Overview

The ResourceService is a powerful CRUD system similar to Open Admin that automatically generates forms, grids, validation, and all CRUD operations for your Laravel models. It provides a fluent API for defining resources and their behavior.

## Table of Contents

1. [Quick Start](#quick-start)
2. [Basic Usage](#basic-usage)
3. [Field Types](#field-types)
4. [Validation](#validation)
5. [Search and Filtering](#search-and-filtering)
6. [Actions](#actions)
7. [Bulk Actions](#bulk-actions)
8. [Customization](#customization)
9. [Examples](#examples)
10. [API Reference](#api-reference)

## Quick Start

### 1. Create a Resource Controller

```php
<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Models\User;
use Modules\UserPanel\Http\Base\ResourceController;
use Modules\UserPanel\Services\ResourceService;

class UserResourceController extends ResourceController
{
    protected function makeResource(): ResourceService
    {
        return (new ResourceService(User::class, 'users'))
            ->title('User Management')
            ->description('Manage users with full CRUD operations')
            
            // Define fields with fluent API
            ->text('name')
                ->required()
                ->searchable()
                ->sortable()
                ->rules(['max:255'])
            
            ->email('email')
                ->required()
                ->searchable()
                ->sortable()
                ->rules(['email', 'unique:users,email'])
            
            ->password('password')
                ->required()
                ->rules(['min:8']);
    }
}
```

### 2. Add Routes

```php
// In your routes file
Route::resource('users', UserResourceController::class);
```

### 3. That's it!

Your resource now has:
- ✅ Automatic CRUD operations
- ✅ Form generation with validation
- ✅ Grid view with sorting and filtering
- ✅ Search functionality
- ✅ Bulk actions
- ✅ Responsive design

## Basic Usage

### Creating a Resource

```php
$resource = new ResourceService(Model::class, 'resource-name');
```

### Defining Fields with Fluent API

```php
$resource->text('name')           // Text input
    ->required()                   // Make required
    ->searchable()                 // Enable search
    ->sortable()                   // Enable sorting
    ->rules(['max:255']);          // Add validation rules
```

### Field Types

#### Text Fields

```php
// Basic text field
$resource->text('name')
    ->required()
    ->searchable()
    ->sortable();

// Textarea field
$resource->textarea('description')
    ->required()
    ->searchable();

// Email field
$resource->email('email')
    ->required()
    ->searchable()
    ->sortable()
    ->rules(['email', 'unique:users,email']);

// Password field
$resource->password('password')
    ->required()
    ->rules(['min:8']);

// Number field
$resource->number('age')
    ->required()
    ->sortable()
    ->rules(['numeric', 'min:0']);

// File upload
$resource->file('avatar')
    ->rules(['image', 'max:2048']);
```

#### Select Fields

```php
// Basic select
$resource->select('status')
    ->options([
        'active' => 'Active',
        'inactive' => 'Inactive'
    ])
    ->filterable(['type' => 'select']);

// Select with callback
$resource->select('user_id')
    ->options(function() {
        return User::all()->pluck('name', 'id')->toArray();
    })
    ->required()
    ->filterable(['type' => 'select']);
```

#### Date Fields

```php
// Date field
$resource->date('birth_date')
    ->sortable()
    ->filterable(['type' => 'date']);

// Datetime field
$resource->datetime('created_at')
    ->sortable()
    ->display(function($value) {
        return $value ? date('M d, Y', strtotime($value)) : 'N/A';
    });
```

#### Boolean Fields

```php
// Checkbox
$resource->checkbox('is_active')
    ->filterable(['type' => 'select', 'options' => ['1' => 'Active', '0' => 'Inactive']])
    ->display(function($value) {
        return $value ? 'Yes' : 'No';
    });

// Radio buttons
$resource->radio('gender')
    ->options([
        'male' => 'Male',
        'female' => 'Female'
    ])
    ->required();
```

## Validation

### Adding Validation Rules

```php
$resource->text('name')
    ->required()
    ->rules(['max:255', 'min:2']);

$resource->email('email')
    ->required()
    ->rules(['email', 'unique:users,email']);
```

### Custom Validation Messages

```php
$resource->text('name')
    ->required()
    ->rules(['max:255'])
    ->messages([
        'required' => 'Name is required',
        'max' => 'Name cannot exceed 255 characters'
    ]);
```

## Search and Filtering

### Searchable Fields

```php
$resource->text('name')
    ->searchable();  // Enable search for this field
```

### Sortable Fields

```php
$resource->text('name')
    ->sortable();    // Enable sorting for this field
```

### Filterable Fields

```php
// Text filter
$resource->text('status')
    ->filterable(['type' => 'text']);

// Select filter
$resource->select('status')
    ->filterable([
        'type' => 'select',
        'options' => [
            'active' => 'Active',
            'inactive' => 'Inactive'
        ]
    ]);

// Date range filter
$resource->date('created_at')
    ->filterable(['type' => 'date']);
```

## Actions

### Default Actions

The ResourceService comes with default actions:
- **View**: View record details
- **Edit**: Edit record
- **Delete**: Delete record (with confirmation)

### Custom Actions

```php
$resource->actions([
    'view' => [
        'label' => 'View',
        'icon' => 'fa fa-eye',
        'class' => 'btn-sm btn-info',
        'route' => 'show'
    ],
    'edit' => [
        'label' => 'Edit',
        'icon' => 'fa fa-edit',
        'class' => 'btn-sm btn-primary',
        'route' => 'edit'
    ],
    'delete' => [
        'label' => 'Delete',
        'icon' => 'fa fa-trash',
        'class' => 'btn-sm btn-danger',
        'route' => 'destroy',
        'method' => 'DELETE',
        'confirm' => true
    ],
    'custom' => [
        'label' => 'Custom Action',
        'icon' => 'fa fa-star',
        'class' => 'btn-sm btn-warning',
        'route' => 'custom',
        'method' => 'POST'
    ]
]);
```

## Bulk Actions

### Defining Bulk Actions

```php
$resource->bulkActions([
    'delete' => [
        'label' => 'Delete Selected',
        'icon' => 'fa fa-trash',
        'class' => 'btn-danger',
        'confirm' => true
    ],
    'activate' => [
        'label' => 'Activate Selected',
        'icon' => 'fa fa-check',
        'class' => 'btn-success'
    ],
    'deactivate' => [
        'label' => 'Deactivate Selected',
        'icon' => 'fa fa-times',
        'class' => 'btn-warning'
    ]
]);
```

## Customization

### Custom Display

```php
$resource->text('status')
    ->display(function($value) {
        if ($value === 'active') {
            return '<span class="badge badge-success">Active</span>';
        }
        return '<span class="badge badge-danger">Inactive</span>';
    });
```

### Custom Form Layout

```php
// Override the buildForm method in your controller
protected function buildForm(FormService $form): void
{
    $form->clear();
    
    // Custom layout
    $headerRow = $form->row();
    $headerRow->column(12, function ($form, $column) {
        $column->addHtml('<h3>Custom Header</h3>');
    });
    $form->addLayoutItem($headerRow);
    
    // Add fields
    $row = $form->row();
    $row->column(6, function ($form, $column) {
        $column->addField($form->text()->name('name')->label('Name'));
    });
    $row->column(6, function ($form, $column) {
        $column->addField($form->email()->name('email')->label('Email'));
    });
    $form->addLayoutItem($row);
}
```

## Examples

### Complete User Resource

```php
<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Models\User;
use Modules\UserPanel\Http\Base\ResourceController;
use Modules\UserPanel\Services\ResourceService;

class UserResourceController extends ResourceController
{
    protected function makeResource(): ResourceService
    {
        return (new ResourceService(User::class, 'users'))
            ->title('User Management')
            ->description('Manage users with full CRUD operations')
            
            // Basic fields
            ->text('name')
                ->required()
                ->searchable()
                ->sortable()
                ->rules(['max:255'])
            
            ->email('email')
                ->required()
                ->searchable()
                ->sortable()
                ->rules(['email', 'unique:users,email'])
            
            ->password('password')
                ->required()
                ->rules(['min:8'])
            
            // Status field with custom display
            ->select('email_verified_at')
                ->filterable([
                    'type' => 'select',
                    'options' => [
                        'verified' => 'Verified',
                        'unverified' => 'Not Verified'
                    ]
                ])
                ->display(function($value) {
                    if ($value) {
                        return '<span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Verified</span>';
                    }
                    return '<span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Unverified</span>';
                })
            
            // Date field with custom display
            ->date('created_at')
                ->sortable()
                ->display(function($value) {
                    return $value ? date('M d, Y', strtotime($value)) : 'N/A';
                })
            
            // Actions
            ->actions([
                'view' => [
                    'label' => 'View',
                    'icon' => 'fa fa-eye',
                    'class' => 'btn-sm btn-info',
                    'route' => 'show'
                ],
                'edit' => [
                    'label' => 'Edit',
                    'icon' => 'fa fa-edit',
                    'class' => 'btn-sm btn-primary',
                    'route' => 'edit'
                ],
                'delete' => [
                    'label' => 'Delete',
                    'icon' => 'fa fa-trash',
                    'class' => 'btn-sm btn-danger',
                    'route' => 'destroy',
                    'method' => 'DELETE',
                    'confirm' => true
                ]
            ])
            
            // Bulk actions
            ->bulkActions([
                'delete' => [
                    'label' => 'Delete Selected',
                    'icon' => 'fa fa-trash',
                    'class' => 'btn-danger',
                    'confirm' => true
                ],
                'verify' => [
                    'label' => 'Verify Selected',
                    'icon' => 'fa fa-check',
                    'class' => 'btn-success'
                ]
            ]);
    }
}
```

### Product Resource

```php
<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Models\Product;
use Modules\UserPanel\Http\Base\ResourceController;
use Modules\UserPanel\Services\ResourceService;

class ProductResourceController extends ResourceController
{
    protected function makeResource(): ResourceService
    {
        return (new ResourceService(Product::class, 'products'))
            ->title('Product Management')
            ->description('Manage products with full CRUD operations')
            
            ->text('name')
                ->required()
                ->searchable()
                ->sortable()
            
            ->textarea('description')
                ->required()
                ->searchable()
            
            ->number('price')
                ->required()
                ->sortable()
                ->rules(['numeric', 'min:0'])
            
            ->select('category_id')
                ->required()
                ->filterable([
                    'type' => 'select',
                    'options' => function() {
                        return Category::all()->pluck('name', 'id')->toArray();
                    }
                ])
            
            ->file('image')
                ->rules(['image', 'max:2048'])
            
            ->checkbox('is_active')
                ->filterable([
                    'type' => 'select',
                    'options' => [
                        '1' => 'Active',
                        '0' => 'Inactive'
                    ]
                ])
                ->display(function($value) {
                    return $value ? 'Yes' : 'No';
                });
    }
}
```

## API Reference

### ResourceService Methods

| Method | Description | Parameters | Returns |
|--------|-------------|------------|---------|
| `__construct()` | Create new resource | `$modelClass`, `$resourceName` | ResourceService |
| `title()` | Set resource title | `$title` | self |
| `description()` | Set resource description | `$description` | self |
| `routePrefix()` | Set route prefix | `$prefix` | self |
| `field()` | Define a field | `$name`, `$type`, `$options` | FieldBuilder |
| `text()` | Define text field | `$name`, `$options` | FieldBuilder |
| `textarea()` | Define textarea field | `$name`, `$options` | FieldBuilder |
| `email()` | Define email field | `$name`, `$options` | FieldBuilder |
| `password()` | Define password field | `$name`, `$options` | FieldBuilder |
| `number()` | Define number field | `$name`, `$options` | FieldBuilder |
| `select()` | Define select field | `$name`, `$options` | FieldBuilder |
| `checkbox()` | Define checkbox field | `$name`, `$options` | FieldBuilder |
| `radio()` | Define radio field | `$name`, `$options` | FieldBuilder |
| `file()` | Define file field | `$name`, `$options` | FieldBuilder |
| `date()` | Define date field | `$name`, `$options` | FieldBuilder |
| `datetime()` | Define datetime field | `$name`, `$options` | FieldBuilder |
| `actions()` | Configure actions | `$actions` | self |
| `bulkActions()` | Configure bulk actions | `$actions` | self |
| `index()` | Generate index view | None | string |
| `create()` | Generate create form | None | array |
| `edit()` | Generate edit form | `$id` | array |
| `store()` | Handle form submission | `$request` | array |
| `update()` | Handle form update | `$request`, `$id` | array |

### FieldBuilder Methods

| Method | Description | Parameters | Returns |
|--------|-------------|------------|---------|
| `required()` | Make field required | None | self |
| `searchable()` | Make field searchable | None | self |
| `sortable()` | Make field sortable | None | self |
| `filterable()` | Make field filterable | `$options` | self |
| `rules()` | Add validation rules | `$rules` | self |
| `display()` | Set custom display | `$callback` | self |
| `options()` | Set options for select/radio | `$options` | self |
| `end()` | Return to resource | None | ResourceService |

### Field Options

| Option | Type | Description | Default |
|--------|------|-------------|---------|
| `label` | string | Field label | Auto-generated |
| `required` | bool | Is field required | false |
| `searchable` | bool | Is field searchable | false |
| `sortable` | bool | Is field sortable | false |
| `filterable` | bool | Is field filterable | false |
| `options` | array | Options for select/radio | [] |
| `validation` | array | Validation rules | [] |
| `display` | callable | Custom display function | null |

## Best Practices

1. **Use meaningful resource names**: Choose descriptive names for your resources
2. **Group related fields**: Use the layout system to group related fields
3. **Add proper validation**: Always add appropriate validation rules
4. **Use custom displays**: Use custom display functions for better UX
5. **Configure actions**: Customize actions based on your needs
6. **Add bulk actions**: Provide bulk operations for better efficiency
7. **Test thoroughly**: Test all CRUD operations before deployment

## Troubleshooting

### Common Issues

1. **Fields not showing**: Make sure fields are properly defined in the resource
2. **Validation not working**: Check that validation rules are correctly set
3. **Routes not found**: Ensure routes are properly registered
4. **Form not submitting**: Check CSRF token and form method
5. **Grid not loading**: Verify that the DataViewService is working correctly

### Debug Mode

Enable debug mode to see detailed error messages:

```php
// In your controller
protected function makeResource(): ResourceService
{
    return (new ResourceService(User::class, 'users'))
        ->debug(true)  // Enable debug mode
        // ... rest of configuration
}
```

## Conclusion

The ResourceService provides a powerful, flexible, and easy-to-use CRUD system similar to Open Admin. With its fluent API and automatic generation capabilities, you can quickly build robust admin panels for your Laravel applications.

For more information and examples, check the source code and existing implementations in your project. 