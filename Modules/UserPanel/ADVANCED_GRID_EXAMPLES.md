# Advanced Grid Query Handling Examples

This document demonstrates how to use the enhanced grid filtering and query handling capabilities in your Laravel admin panel.

## 1. Basic Filter Types

### Text Filters
```php
$dataView->addTextFilter('name', 'Product Name');
$dataView->addTextFilter('sku', 'SKU');
```

### Select Filters
```php
$dataView->addFilter('status', 'Status', [
    'active' => 'Active',
    'inactive' => 'Inactive',
    'draft' => 'Draft'
], 'select');
```

### Date Range Filters
```php
$dataView->addDateRangeFilter('created_at', 'Created Date');
$dataView->addDateRangeFilter('updated_at', 'Last Modified');
```

## 2. Advanced Filter Types

### Numeric Range Filters
```php
// Price range filter
$dataView->addNumericRangeFilter('price', 'Price Range');

// Stock range filter
$dataView->addNumericRangeFilter('stock', 'Stock Range');

// Age range filter
$dataView->addNumericRangeFilter('age', 'Age Range');
```

### Relationship Filters
```php
// Filter by category relationship
$dataView->addRelationshipFilter('category_id', 'Category', 'category', 'name');

// Filter by vendor relationship
$dataView->addRelationshipFilter('vendor_id', 'Vendor', 'vendor', 'company_name');

// Filter by author relationship
$dataView->addRelationshipFilter('author_id', 'Author', 'author', 'full_name');
```

### Custom Filters with Closures
```php
// Availability filter with custom logic
$dataView->addCustomFilter('availability', 'Availability', function($query, $value) {
    switch ($value) {
        case 'in_stock':
            $query->where('stock', '>', 0);
            break;
        case 'low_stock':
            $query->whereBetween('stock', [1, 10]);
            break;
        case 'out_of_stock':
            $query->where('stock', 0);
            break;
    }
});

// Complex status filter
$dataView->addCustomFilter('complex_status', 'Complex Status', function($query, $value) {
    if ($value === 'active_with_stock') {
        $query->where('is_active', true)
              ->where('stock', '>', 0);
    } elseif ($value === 'inactive_or_no_stock') {
        $query->where(function($q) {
            $q->where('is_active', false)
              ->orWhere('stock', 0);
        });
    }
});
```

## 3. Query Scopes

### Basic Query Scope
```php
$dataView->addQueryScope(function($query) {
    // Only show active items
    $query->where('is_active', true);
    
    // Apply user role restrictions
    if (auth()->user()->role === 'vendor') {
        $query->where('vendor_id', auth()->id());
    }
});
```

### Advanced Query Scope with URL Parameters
```php
$dataView->addQueryScope(function($query) {
    // Apply category filter from URL
    if (request('category_id')) {
        $query->where('category_id', request('category_id'));
    }
    
    // Apply price range from URL
    if (request('min_price') || request('max_price')) {
        if (request('min_price')) {
            $query->where('price', '>=', request('min_price'));
        }
        if (request('max_price')) {
            $query->where('price', '<=', request('max_price'));
        }
    }
    
    // Apply date range from URL
    if (request('start_date') || request('end_date')) {
        if (request('start_date')) {
            $query->whereDate('created_at', '>=', request('start_date'));
        }
        if (request('end_date')) {
            $query->whereDate('created_at', '<=', request('end_date'));
        }
    }
    
    // Eager load relationships
    $query->with(['category', 'vendor', 'images']);
});
```

### Performance Optimization Query Scope
```php
$dataView->addQueryScope(function($query) {
    // Select only needed fields
    $query->select(['id', 'name', 'price', 'stock', 'category_id', 'status', 'created_at']);
    
    // Eager load relationships
    $query->with(['category:id,name', 'vendor:id,company_name']);
    
    // Add database indexes for frequently filtered fields
    // Make sure you have indexes on: category_id, price, status, vendor_id, created_at
});
```

## 4. Complete Controller Example

```php
<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\ResourceController;
use Modules\UserPanel\Services\DataViewService;

class AdvancedProductController extends ResourceController
{
    public $icon = 'fa fa-box';
    public $model = Product::class;
    public $routeName = 'products';

    public function dataView()
    {
        $dataView = new DataViewService(new Product());

        // Add multiple query scopes
        $dataView->addQueryScope(function($query) {
            // Business logic: only show active products
            $query->where('is_active', true);
            
            // User role restrictions
            if (auth()->user()->role === 'vendor') {
                $query->where('vendor_id', auth()->id());
            }
            
            // URL parameter filters
            if (request('category_id')) {
                $query->where('category_id', request('category_id'));
            }
            
            if (request('min_price') || request('max_price')) {
                if (request('min_price')) {
                    $query->where('price', '>=', request('min_price'));
                }
                if (request('max_price')) {
                    $query->where('price', '<=', request('max_price'));
                }
            }
            
            // Performance optimization
            $query->with(['category:id,name', 'vendor:id,company_name']);
        });

        // Configure grid
        $dataView->title('Advanced Product Management')
            ->description('Comprehensive product grid with advanced filtering capabilities')
            ->routePrefix('products')
            ->perPage(20)
            ->defaultSort('created_at', 'desc')
            ->pagination(true)
            ->search(true)
            ->filters(true);

        // Add columns
        $dataView->id('ID')->sortable();
        
        $dataView->column('name', 'Product Name')
            ->sortable()
            ->searchable();
            
        $dataView->column('sku', 'SKU')
            ->sortable()
            ->searchable();
            
        $dataView->column('price', 'Price')
            ->display(function($value) {
                return '$' . number_format($value, 2);
            })
            ->sortable();
            
        $dataView->column('stock', 'Stock')
            ->display(function($value) {
                if ($value > 10) {
                    return '<span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">' . $value . '</span>';
                } elseif ($value > 0) {
                    return '<span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">' . $value . '</span>';
                } else {
                    return '<span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Out of Stock</span>';
                }
            })
            ->sortable();
            
        $dataView->column('category_id', 'Category')
            ->display(function($value, $product) {
                return $product->category ? $product->category->name : 'N/A';
            })
            ->sortable();
            
        $dataView->column('status', 'Status')
            ->display(function($value) {
                return $value ? 'Active' : 'Inactive';
            })
            ->sortable();

        // Add all filter types
        $dataView->addTextFilter('name', 'Product Name');
        $dataView->addTextFilter('sku', 'SKU');
        
        $dataView->addFilter('status', 'Status', [
            '1' => 'Active',
            '0' => 'Inactive'
        ], 'select');
        
        $dataView->addFilter('category', 'Category', [
            'electronics' => 'Electronics',
            'clothing' => 'Clothing',
            'books' => 'Books',
            'home' => 'Home & Garden',
            'sports' => 'Sports & Outdoors'
        ], 'select');
        
        $dataView->addNumericRangeFilter('price', 'Price Range');
        $dataView->addNumericRangeFilter('stock', 'Stock Range');
        
        $dataView->addRelationshipFilter('category_id', 'Category', 'category', 'name');
        $dataView->addRelationshipFilter('vendor_id', 'Vendor', 'vendor', 'company_name');
        
        $dataView->addCustomFilter('availability', 'Availability', function($query, $value) {
            switch ($value) {
                case 'in_stock':
                    $query->where('stock', '>', 0);
                    break;
                case 'low_stock':
                    $query->whereBetween('stock', [1, 10]);
                    break;
                case 'out_of_stock':
                    $query->where('stock', 0);
                    break;
            }
        });
        
        $dataView->addDateRangeFilter('created_at', 'Created Date');
        $dataView->addDateRangeFilter('updated_at', 'Last Modified');

        // Add actions
        $dataView->actions([
            [
                'label' => 'View',
                'url' => function($product) {
                    return route('products.show', $product->id);
                },
                'class' => 'px-3 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600'
            ],
            [
                'label' => 'Edit',
                'url' => function($product) {
                    return route('products.edit', $product->id);
                },
                'class' => 'px-3 py-1 text-xs bg-yellow-500 text-white rounded hover:bg-yellow-600'
            ],
            [
                'label' => 'Delete',
                'url' => function($product) {
                    return route('products.destroy', $product->id);
                },
                'class' => 'px-3 py-1 text-xs bg-red-500 text-white rounded hover:bg-red-600',
                'confirm' => true
            ]
        ]);

        // Add bulk actions
        $dataView->bulkActions([
            'activate' => [
                'label' => 'Activate Selected',
                'icon' => 'fa fa-check',
                'class' => 'btn-success'
            ],
            'deactivate' => [
                'label' => 'Deactivate Selected',
                'icon' => 'fa fa-times',
                'class' => 'btn-warning'
            ],
            'delete' => [
                'label' => 'Delete Selected',
                'icon' => 'fa fa-trash',
                'class' => 'btn-danger',
                'confirm' => true
            ]
        ]);

        $dataView->createButton(route('products.create'), 'Create New Product');

        return $dataView;
    }
}
```

## 5. URL Parameter Examples

With the enhanced filtering, you can now use URLs like:

```
/products?filter_status=1&filter_price_min=10&filter_price_max=100&filter_category=electronics
/products?filter_availability=in_stock&filter_stock_min=5&filter_created_at_start=2024-01-01
/products?category_id=5&min_price=50&max_price=200&start_date=2024-01-01
```

## 6. Performance Tips

1. **Add Database Indexes** on frequently filtered fields
2. **Use Eager Loading** to avoid N+1 queries
3. **Limit Selected Fields** when you don't need all columns
4. **Use Query Scopes** for complex business logic
5. **Cache Filter Options** for relationship filters

## 7. Custom Filter Logic

You can create very sophisticated filters:

```php
// Multi-field search filter
$dataView->addCustomFilter('search_all', 'Search All Fields', function($query, $value) {
    $query->where(function($q) use ($value) {
        $q->where('name', 'like', "%{$value}%")
          ->orWhere('description', 'like', "%{$value}%")
          ->orWhere('sku', 'like', "%{$value}%")
          ->orWhereHas('category', function($cat) use ($value) {
              $cat->where('name', 'like', "%{$value}%");
          });
    });
});

// Conditional filter based on user role
$dataView->addCustomFilter('user_specific', 'User Specific', function($query, $value) {
    if (auth()->user()->role === 'admin') {
        // Admins can see everything
        return;
    } elseif (auth()->user()->role === 'manager') {
        // Managers see only their department
        $query->where('department_id', auth()->user()->department_id);
    } else {
        // Regular users see only their own items
        $query->where('user_id', auth()->id());
    }
});
```

This enhanced grid system gives you powerful, flexible, and performant data filtering capabilities while maintaining clean, maintainable code.
