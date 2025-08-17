# Smart Tab Organization Examples

This document demonstrates how to use the new smart tab organization system in your Laravel admin panel.

## Basic Tab Organization

```php
use Modules\UserPanel\Services\ResourceService;

$resource = new ResourceService(Product::class, 'products');

// Enable tabs
$resource->enableTabs();

// Essential Info Tab (High Priority - Shows First)
$resource->tab('essential', 'Essential Info', 'fa fa-star')
    ->priority('high')  // Always show first
    ->fields(['name', 'price', 'category'])
    ->end();

// Advanced Settings Tab (Low Priority - Shows Last)
$resource->tab('advanced', 'Advanced Settings', 'fa fa-cog')
    ->priority('low')   // Show last
    ->collapsible()     // Can be collapsed
    ->end();

// Medium Priority Tab (Shows in middle)
$resource->tab('details', 'Product Details', 'fa fa-info-circle')
    ->priority('medium')
    ->fields(['description', 'sku', 'weight'])
    ->end();
```

## Tab Priority Levels

- **`high`** - Tabs with high priority are displayed first
- **`medium`** - Default priority, displayed in the middle
- **`low`** - Tabs with low priority are displayed last

## Collapsible Tabs

```php
$resource->tab('advanced', 'Advanced Settings', 'fa fa-cog')
    ->priority('low')
    ->collapsible()  // Makes the tab collapsible
    ->fields(['meta_title', 'meta_description', 'seo_keywords'])
    ->end();
```

## Adding Fields to Tabs

### Method 1: Individual Fields
```php
$resource->tab('essential', 'Essential Info', 'fa fa-star')
    ->priority('high')
    ->text('name')->label('Product Name')->required()
    ->number('price')->label('Price')->required()
    ->select('category')->label('Category')->options($categories)
    ->end();
```

### Method 2: Multiple Fields at Once
```php
$resource->tab('essential', 'Essential Info', 'fa fa-star')
    ->priority('high')
    ->fields(['name', 'price', 'category'])  // Add multiple fields
    ->end();
```

## Complex Tab Organization

```php
$resource->enableTabs();

// Essential Info (High Priority)
$resource->tab('essential', 'Essential Info', 'fa fa-star')
    ->priority('high')
    ->fields(['name', 'price', 'category'])
    ->end();

// Product Details (Medium Priority)
$resource->tab('details', 'Product Details', 'fa fa-info-circle')
    ->priority('medium')
    ->fields(['description', 'sku', 'weight', 'dimensions'])
    ->end();

// Media & Files (Medium Priority)
$resource->tab('media', 'Media & Files', 'fa fa-images')
    ->priority('medium')
    ->fields(['main_image', 'gallery', 'documents'])
    ->end();

// Advanced Settings (Low Priority, Collapsible)
$resource->tab('advanced', 'Advanced Settings', 'fa fa-cog')
    ->priority('low')
    ->collapsible()
    ->fields(['meta_title', 'meta_description', 'seo_keywords', 'custom_fields'])
    ->end();

// SEO Settings (Low Priority, Collapsible)
$resource->tab('seo', 'SEO Settings', 'fa fa-search')
    ->priority('low')
    ->collapsible()
    ->fields(['seo_title', 'seo_description', 'canonical_url'])
    ->end();
```

## Tab with Sections

```php
$resource->tab('details', 'Product Details', 'fa fa-info-circle')
    ->priority('medium')
    ->section('Basic Information', 'fa fa-info')
        ->text('name')->label('Product Name')->required()
        ->textarea('description')->label('Description')
        ->endSection()
    ->section('Pricing', 'fa fa-dollar-sign')
        ->number('price')->label('Price')->required()
        ->number('sale_price')->label('Sale Price')
        ->endSection()
    ->end();
```

## Visual Indicators

- **High Priority Tabs**: Bold text, darker color
- **Medium Priority Tabs**: Normal weight, medium color  
- **Low Priority Tabs**: Lighter weight, lighter color
- **Collapsible Tabs**: Show chevron icon that rotates when expanded

## Best Practices

1. **Use High Priority sparingly** - Only for the most essential information
2. **Group related fields** - Keep related fields in the same tab
3. **Use Medium Priority for most tabs** - This is the default and most common
4. **Use Low Priority for advanced/optional settings** - These are less frequently used
5. **Make advanced tabs collapsible** - Helps reduce visual clutter
6. **Limit tabs to 5-7 maximum** - Too many tabs can be overwhelming

## Complete Example

```php
<?php

namespace App\Http\Controllers;

use Modules\UserPanel\Services\ResourceService;
use App\Models\Product;

class ProductController extends Controller
{
    public function create()
    {
        $resource = new ResourceService(Product::class, 'products');
        
        // Define fields
        $resource->text('name')->label('Product Name')->required();
        $resource->textarea('description')->label('Description');
        $resource->number('price')->label('Price')->required();
        $resource->select('category')->label('Category')->options(Category::pluck('name', 'id'));
        $resource->file('image')->label('Product Image');
        $resource->text('sku')->label('SKU');
        $resource->number('weight')->label('Weight (kg)');
        $resource->text('meta_title')->label('Meta Title');
        $resource->textarea('meta_description')->label('Meta Description');
        
        // Enable and organize tabs
        $resource->enableTabs();
        
        // Essential Info (High Priority)
        $resource->tab('essential', 'Essential Info', 'fa fa-star')
            ->priority('high')
            ->fields(['name', 'price', 'category'])
            ->end();
        
        // Product Details (Medium Priority)
        $resource->tab('details', 'Product Details', 'fa fa-info-circle')
            ->priority('medium')
            ->fields(['description', 'sku', 'weight'])
            ->end();
        
        // Media (Medium Priority)
        $resource->tab('media', 'Media', 'fa fa-images')
            ->priority('medium')
            ->fields(['image'])
            ->end();
        
        // SEO (Low Priority, Collapsible)
        $resource->tab('seo', 'SEO Settings', 'fa fa-search')
            ->priority('low')
            ->collapsible()
            ->fields(['meta_title', 'meta_description'])
            ->end();
        
        return view('products.create', $resource->create());
    }
}
```

This system provides a clean, organized way to structure your forms with intelligent tab ordering and collapsible functionality.
