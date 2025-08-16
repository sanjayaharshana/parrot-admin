# Smart Field Validation & Help Text - Usage Examples

## Overview

This document demonstrates how to use the new Smart Field Validation & Help Text features in your Products module. These features provide better user guidance and clearer validation messages.

## New Features Available

### 1. Help Text
Add descriptive text below fields to guide users:

```php
->text('name')
    ->required()
    ->searchable()
    ->sortable()
    ->placeholder('Enter product name')
    ->help('Product name will be displayed to customers and used in search results')
```

### 2. Enhanced Validation Messages
Provide custom, user-friendly validation messages:

```php
->text('email')
    ->required()
    ->validate(['required', 'email'], [
        'required' => 'Please enter an email address',
        'email' => 'Please enter a valid email address'
    ])
```

### 3. Smart Validation
Use the new `validate()` method for cleaner validation:

```php
->number('price')
    ->required()
    ->validate(['min:0', 'numeric'], [
        'min' => 'Price must be greater than or equal to 0',
        'numeric' => 'Price must be a valid number'
    ])
    ->help('Enter the price in your local currency (e.g., 29.99)')
```

## Complete Example Implementation

Here's how to update your ProductsController with these new features:

```php
protected function makeResource(): ResourceService
{
    return (new ResourceService(Products::class, 'products'))
        ->title('Products Management')
        ->description('Manage products records')
        ->enableTabs()
        
        // Basic Information Tab
        ->tab('basic', 'Basic Information', 'fa fa-info-circle')
            ->text('name')
                ->required()
                ->searchable()
                ->sortable()
                ->placeholder('Enter product name')
                ->help('Product name will be displayed to customers and used in search results')
                ->validate(['required', 'min:3', 'max:100'], [
                    'required' => 'Product name is required',
                    'min' => 'Product name must be at least 3 characters',
                    'max' => 'Product name cannot exceed 100 characters'
                ])
            ->textarea('description')
                ->placeholder('Enter product description')
                ->height(120)
                ->help('Provide a detailed description that helps customers understand your product')
                ->validate(['required', 'min:10'], [
                    'required' => 'Product description is required',
                    'min' => 'Description should be at least 10 characters long'
                ])
            ->number('price')
                ->required()
                ->searchable()
                ->sortable()
                ->placeholder('0.00')
                ->help('Enter the price in your local currency (e.g., 29.99)')
                ->validate(['required', 'min:0', 'numeric'], [
                    'required' => 'Product price is required',
                    'min' => 'Price must be greater than or equal to 0',
                    'numeric' => 'Price must be a valid number'
                ])
            ->text('sku')
                ->searchable()
                ->sortable()
                ->placeholder('Stock Keeping Unit')
                ->help('Unique identifier for inventory management (leave empty to auto-generate)')
                ->validate(['nullable', 'unique:products,sku'], [
                    'unique' => 'This SKU is already in use by another product'
                ])
            ->text('slug')
                ->searchable()
                ->sortable()
                ->placeholder('product-url-slug')
                ->help('URL-friendly version of the product name (e.g., "wireless-headphones")')
                ->validate(['nullable', 'regex:/^[a-z0-9-]+$/'], [
                    'regex' => 'Slug can only contain lowercase letters, numbers, and hyphens'
                ])
            ->select('category')
                ->searchable()
                ->sortable()
                ->options([
                    'electronics' => 'Electronics',
                    'clothing' => 'Clothing',
                    'books' => 'Books',
                    'home' => 'Home & Garden',
                    'sports' => 'Sports & Outdoors',
                    'other' => 'Other'
                ])
                ->placeholder('Select category')
                ->help('Choose the most appropriate category for your product')
                ->validate(['required'], [
                    'required' => 'Please select a product category'
                ])
            ->select('brand')
                ->searchable()
                ->sortable()
                ->options([
                    'apple' => 'Apple',
                    'samsung' => 'Samsung',
                    'nike' => 'Nike',
                    'adidas' => 'Adidas',
                    'generic' => 'Generic',
                    'other' => 'Other'
                ])
                ->placeholder('Select brand')
                ->help('Select the brand or choose "Generic" for unbranded products')
                ->validate(['required'], [
                    'required' => 'Please select a product brand'
                ])
            ->end()
        
        // Media & Images Tab
        ->tab('media', 'Media & Images', 'fa fa-image')
            ->file('image')
                ->accept('image/*')
                ->placeholder('Upload main product image')
                ->help('Upload a high-quality image (800x800px recommended, max 2MB)')
                ->validate(['nullable', 'image', 'max:2048'], [
                    'image' => 'Please upload a valid image file',
                    'max' => 'Image size must be less than 2MB'
                ])
            ->text('thumbnail')
                ->searchable()
                ->sortable()
                ->placeholder('Thumbnail URL or path')
                ->help('Path to the thumbnail image (auto-generated if left empty)')
            ->customHtml('
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fa fa-info-circle text-blue-500 mr-2"></i>
                        <span class="text-blue-800 text-sm">
                            <strong>Image Guidelines:</strong> Recommended size: 800x800px, Max file size: 2MB, 
                            Supported formats: JPG, PNG, GIF, WebP
                        </span>
                    </div>
                </div>
            ', 'Image Guidelines', 'mb-4')
            ->end()
        
        // Inventory & Stock Tab
        ->tab('inventory', 'Inventory & Stock', 'fa fa-boxes')
            ->number('stock_quantity')
                ->searchable()
                ->sortable()
                ->placeholder('Available quantity')
                ->help('Current available stock quantity (0 for out of stock)')
                ->validate(['nullable', 'min:0', 'integer'], [
                    'min' => 'Stock quantity cannot be negative',
                    'integer' => 'Stock quantity must be a whole number'
                ])
            ->select('stock_status')
                ->searchable()
                ->sortable()
                ->options([
                    'in_stock' => 'In Stock',
                    'out_of_stock' => 'Out of Stock',
                    'low_stock' => 'Low Stock',
                    'backorder' => 'Backorder',
                    'discontinued' => 'Discontinued'
                ])
                ->placeholder('Select stock status')
                ->help('Current availability status of the product')
                ->validate(['required'], [
                    'required' => 'Please select a stock status'
                ])
            ->number('weight')
                ->searchable()
                ->sortable()
                ->placeholder('Weight in grams')
                ->help('Product weight in grams (used for shipping calculations)')
                ->validate(['nullable', 'min:0', 'numeric'], [
                    'min' => 'Weight cannot be negative',
                    'numeric' => 'Weight must be a valid number'
                ])
            ->text('dimensions')
                ->searchable()
                ->sortable()
                ->placeholder('L x W x H (cm)')
                ->help('Product dimensions in centimeters (e.g., "30 x 20 x 10")')
            ->text('barcode')
                ->searchable()
                ->sortable()
                ->placeholder('Product barcode/UPC')
                ->help('Product barcode or UPC code for scanning')
            ->end()
        
        // Pricing & Subscription Tab
        ->tab('pricing', 'Pricing & Subscription', 'fa fa-dollar-sign')
            ->number('subscription_price')
                ->searchable()
                ->sortable()
                ->placeholder('Monthly subscription price')
                ->help('Monthly subscription price (leave empty if not a subscription product)')
                ->validate(['nullable', 'min:0', 'numeric'], [
                    'min' => 'Subscription price cannot be negative',
                    'numeric' => 'Subscription price must be a valid number'
                ])
            ->select('subscription_interval')
                ->searchable()
                ->sortable()
                ->options([
                    'monthly' => 'Monthly',
                    'quarterly' => 'Quarterly',
                    'yearly' => 'Yearly',
                    'weekly' => 'Weekly',
                    'daily' => 'Daily'
                ])
                ->placeholder('Select subscription interval')
                ->help('Billing frequency for subscription products')
                ->validate(['nullable', 'required_if:is_subscription,1'], [
                    'required_if' => 'Subscription interval is required for subscription products'
                ])
            ->textarea('subscription_terms')
                ->placeholder('Subscription terms and conditions')
                ->height(100)
                ->help('Terms and conditions for subscription products')
            ->end()
        
        // Product Settings Tab
        ->tab('settings', 'Product Settings', 'fa fa-cog')
            ->switch('is_active')
                ->searchable()
                ->sortable()
                ->label('Product Active')
                ->help('Enable this to make the product visible to customers')
            ->switch('is_featured')
                ->searchable()
                ->sortable()
                ->label('Featured Product')
                ->help('Featured products appear prominently on the homepage')
            ->switch('is_visible')
                ->searchable()
                ->sortable()
                ->label('Visible to Customers')
                ->help('Control whether customers can see this product')
            ->switch('is_featured_on_homepage')
                ->searchable()
                ->sortable()
                ->label('Featured on Homepage')
                ->help('Display this product in the homepage featured section')
            ->switch('is_new')
                ->searchable()
                ->sortable()
                ->label('New Product')
                ->help('Mark as new product (shows "New" badge)')
            ->switch('is_on_sale')
                ->searchable()
                ->sortable()
                ->label('On Sale')
                ->help('Mark as on sale (shows sale badge and pricing)')
            ->switch('is_taxable')
                ->searchable()
                ->sortable()
                ->label('Taxable Product')
                ->help('Apply sales tax to this product')
            ->switch('is_digital')
                ->searchable()
                ->sortable()
                ->label('Digital Product')
                ->help('Digital products are delivered electronically')
            ->switch('is_subscription')
                ->searchable()
                ->sortable()
                ->label('Subscription Product')
                ->help('Recurring billing product')
            ->number('sort_order')
                ->searchable()
                ->sortable()
                ->placeholder('Display order (lower numbers first)')
                ->help('Control the display order of products (lower numbers appear first)')
                ->validate(['nullable', 'integer'], [
                    'integer' => 'Sort order must be a whole number'
                ])
            ->end()
        
        // SEO & Meta Tab
        ->tab('seo', 'SEO & Meta', 'fa fa-search')
            ->text('meta_title')
                ->searchable()
                ->sortable()
                ->placeholder('SEO title (max 60 characters)')
                ->help('SEO title for search engines (keep under 60 characters for best results)')
                ->validate(['nullable', 'max:60'], [
                    'max' => 'Meta title cannot exceed 60 characters'
                ])
            ->textarea('meta_description')
                ->placeholder('SEO description (max 160 characters)')
                ->height(100)
                ->help('SEO description for search engines (keep under 160 characters)')
                ->validate(['nullable', 'max:160'], [
                    'max' => 'Meta description cannot exceed 160 characters'
                ])
            ->text('meta_keywords')
                ->searchable()
                ->sortable()
                ->placeholder('SEO keywords (comma separated)')
                ->help('Comma-separated keywords for SEO (e.g., "wireless, headphones, bluetooth")')
            ->end()
        
        // Additional Information Tab
        ->tab('additional', 'Additional Information', 'fa fa-plus-circle')
            ->text('vendor')
                ->searchable()
                ->sortable()
                ->placeholder('Product vendor/supplier')
                ->help('Name of the vendor or supplier')
            ->text('warranty')
                ->searchable()
                ->sortable()
                ->placeholder('Warranty information')
                ->help('Warranty details (e.g., "1 year limited warranty")')
            ->textarea('additional_info')
                ->placeholder('Additional product information')
                ->height(100)
                ->help('Any additional information that might be useful to customers')
            ->textarea('custom_fields')
                ->placeholder('Custom fields (JSON format)')
                ->height(100)
                ->help('Store additional custom data in JSON format')
            ->end()
        
        ->actions([
            'view' => [ 'label' => 'View', 'icon' => 'fa fa-eye', 'route' => 'show' ],
            'edit' => [ 'label' => 'Edit', 'icon' => 'fa fa-edit', 'route' => 'edit' ],
            'delete' => [ 'label' => 'Delete', 'icon' => 'fa fa-trash', 'route' => 'destroy', 'method' => 'DELETE', 'confirm' => true ],
        ])
        ->bulkActions([
            'delete' => [ 'label' => 'Delete Selected', 'icon' => 'fa fa-trash', 'confirm' => true ],
        ]);
}
```

## Benefits of These Features

### 1. **Better User Experience**
- Clear guidance on what to enter
- Helpful tips and examples
- Professional appearance

### 2. **Improved Validation**
- Custom, user-friendly error messages
- Clear validation rules
- Better error handling

### 3. **Easier Maintenance**
- Centralized validation logic
- Consistent error messages
- Easy to update and modify

### 4. **Professional Forms**
- Help text provides context
- Validation messages guide users
- Better accessibility

## Available Validation Methods

### Basic Validation
```php
->rule('required')           // Single rule
->rules(['required', 'email']) // Multiple rules
```

### Smart Validation
```php
->validate(['required', 'email'], [
    'required' => 'Custom message',
    'email' => 'Custom email message'
])
```

### Help Text
```php
->help('Your helpful text here')
```

### Combined Usage
```php
->text('email')
    ->required()
    ->help('We\'ll never share your email with anyone else')
    ->validate(['email'], [
        'email' => 'Please enter a valid email address'
    ])
```

## Best Practices

1. **Keep help text concise** - 1-2 sentences maximum
2. **Use validation messages** - Customize error messages for better UX
3. **Group related fields** - Use tabs to organize complex forms
4. **Provide examples** - Show users what to expect
5. **Be consistent** - Use similar patterns across your forms

This implementation provides a much more professional and user-friendly form experience while maintaining the clean, organized structure you already have!
