# Simple Route Setup Guide

This guide shows how easy it is to set up CRUD routing using the new property-based approach.

## Quick Setup

### 1. Controller Setup (Super Simple!)

```php
<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Models\YourModel;
use Modules\UserPanel\Http\Base\BaseController;

class YourController extends BaseController
{
    // Just set these 3 properties and you're done!
    public $icon = 'fa fa-icon';
    public $model = YourModel::class;
    public $routeName = 'your-resource'; // That's it!
}
```

### 2. Route Registration

```php
// In routes/web.php
Route::resource('/your-resource', YourController::class);
```

### 3. That's All!

The system automatically:
- ✅ Sets up all CRUD routes
- ✅ Handles form routing (POST for create, PUT for edit)
- ✅ Manages form actions and methods
- ✅ Provides data grids and forms

## Examples

### Products Controller
```php
class ProductController extends BaseController
{
    public $icon = 'fa fa-box';
    public $model = Product::class;
    public $routeName = 'products';
}
```

### Users Controller
```php
class UserController extends BaseController
{
    public $icon = 'fa fa-users';
    public $model = User::class;
    public $routeName = 'users';
}
```

### Dashboard Controller
```php
class DashboardController extends BaseController
{
    public $icon = 'fa fa-dashboard';
    public $model = Evest::class;
    public $routeName = 'dashboard';
}
```

## Automatic Route Generation

When you set `public $routeName = 'your-resource'`, the system automatically creates:

| Action | Route | Method | Controller Method |
|--------|-------|--------|-------------------|
| List | `/your-resource` | GET | `index()` |
| Create Form | `/your-resource/create` | GET | `create()` |
| Store | `/your-resource` | POST | `store()` |
| Show | `/your-resource/{id}` | GET | `show()` |
| Edit Form | `/your-resource/{id}/edit` | GET | `edit()` |
| Update | `/your-resource/{id}` | PUT | `update()` |
| Delete | `/your-resource/{id}` | DELETE | `destroy()` |

## Form Routing

The FormService automatically sets the correct routes:

```php
// In create() method
$this->form->routeForStore($this->getRouteName());
// Results in: action="/your-resource" method="POST"

// In edit() method  
$this->form->routeForUpdate($this->getRouteName(), $id);
// Results in: action="/your-resource/123" method="PUT"
```

## Benefits

- ✅ **Super Simple**: Just set 3 properties
- ✅ **No Boilerplate**: No need to write CRUD methods
- ✅ **Automatic Routing**: Routes are set automatically
- ✅ **Consistent**: Same pattern for all controllers
- ✅ **Maintainable**: Easy to understand and modify

## Complete Example

```php
<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Models\Product;
use Modules\UserPanel\Http\Base\BaseController;

class ProductController extends BaseController
{
    public $icon = 'fa fa-box';
    public $model = Product::class;
    public $routeName = 'products';

    // Optional: Customize your form
    public function createForm()
    {
        $layout = $this->layoutService;
        $layout->setFormService($this->form);
        
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

    // Optional: Customize your data grid
    public function dataSetView()
    {
        $grid = new DataViewService(new Product());
        $grid->title('Product Management');
        $grid->column('name', 'Product Name')->sortable();
        return $grid->render();
    }
}
```

That's it! Your CRUD system is ready to use. 🚀 