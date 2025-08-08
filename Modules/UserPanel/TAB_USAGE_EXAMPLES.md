# Tab Functionality in UserPanel Forms

The UserPanel module provides a powerful tab system for organizing form fields into logical groups. This document explains how to use tabs effectively in your forms.

## Overview

Tabs allow you to organize form fields into logical sections, making complex forms more user-friendly and easier to navigate. Each tab can contain multiple fields, dividers, alerts, and custom HTML content.

## Basic Tab Implementation

### 1. Enable Tabs

To use tabs in your form, first enable them in your ResourceService:

```php
protected function makeResource(): ResourceService
{
    return (new ResourceService(User::class, 'users'))
        ->title('User Management')
        ->description('Manage users with organized tabbed forms')
        
        // Enable tabs for better organization
        ->enableTabs()
        
        // ... rest of your configuration
}
```

### 2. Create Tabs

Use the `tab()` method to create tabs. Each tab requires:
- **ID**: Unique identifier for the tab
- **Label**: Display name for the tab
- **Icon**: FontAwesome icon class (optional)

```php
->tab('basic', 'Basic Information', 'fa fa-user')
    // Add fields to this tab
    ->text('name')
        ->required()
        ->searchable()
        ->sortable()
    ->text('email')
        ->required()
        ->searchable()
        ->sortable()
    ->end() // End the tab and return to ResourceService
```

### 3. Method Chaining

The `TabBuilder` methods return the `TabBuilder` instance, allowing you to chain multiple field methods:

```php
->tab('profile', 'Profile Details', 'fa fa-id-card')
    ->textarea('bio')
        ->searchable()
        ->rules(['max:1000'])
    ->select('role')
        ->required()
        ->searchable()
        ->sortable()
        ->options([
            'user' => 'Regular User',
            'admin' => 'Administrator',
            'moderator' => 'Moderator'
        ])
        ->rules(['in:user,admin,moderator'])
    ->checkbox('is_active')
        ->searchable()
        ->sortable()
    ->divider('Role Information')
    ->alert('Please select the appropriate role for this user.', 'info')
    ->end() // This is required!
```

## Tab Content Types

### Fields

All standard form fields can be added to tabs with method chaining:

```php
->tab('basic', 'Basic Information')
    ->text('name')
        ->required()
        ->searchable()
        ->sortable()
        ->rules(['max:255'])
    ->email('email')
        ->required()
        ->searchable()
        ->sortable()
        ->rules(['email', 'max:255'])
    ->password('password')
        ->required()
        ->rules(['min:8'])
    ->textarea('description')
        ->rules(['max:1000'])
    ->select('category')
        ->required()
        ->options(['option1' => 'Label 1', 'option2' => 'Label 2'])
    ->checkbox('is_active')
        ->searchable()
        ->sortable()
    ->radio('status')
        ->options(['active' => 'Active', 'inactive' => 'Inactive'])
    ->file('avatar')
        ->rules(['image', 'max:2048'])
    ->date('birth_date')
        ->rules(['date'])
    ->datetime('last_login')
        ->rules(['date'])
    ->number('age')
        ->rules(['numeric', 'min:0'])
    ->end()
```

### Dividers

Add visual separators within tabs:

```php
->tab('profile', 'Profile Details')
    ->text('first_name')
        ->required()
    ->text('last_name')
        ->required()
    ->divider('Contact Information') // Adds a horizontal line with text
    ->text('phone')
        ->rules(['max:20'])
    ->text('address')
        ->rules(['max:500'])
    ->end()
```

### Alerts

Add informational, warning, or error messages:

```php
->tab('settings', 'Settings')
    ->checkbox('email_notifications')
        ->searchable()
    ->checkbox('sms_notifications')
        ->searchable()
    ->alert('You will receive email notifications for important updates.', 'info')
    ->alert('Please ensure your email address is correct.', 'warning')
    ->alert('Invalid settings may cause issues.', 'error')
    ->end()
```

Alert types: `info`, `warning`, `error`, `success`

### Custom HTML

Add custom HTML content for complex layouts:

```php
->tab('help', 'Help & Information')
    ->customHtml(
        '<div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h4 class="font-medium text-blue-800 mb-2">Quick Tips:</h4>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>Use descriptive names for better organization</li>
                <li>Fill in all required fields marked with *</li>
                <li>Contact support if you need assistance</li>
            </ul>
        </div>',
        'Quick Tips',
        'bg-blue-50 border border-blue-200 rounded-lg p-4'
    )
    ->end()
```

## Complete Example

Here's a complete example showing how to create a comprehensive tabbed form:

```php
protected function makeResource(): ResourceService
{
    return (new ResourceService(Product::class, 'products'))
        ->title('Product Management')
        ->description('Manage products with organized tabbed forms')
        ->enableTabs()

        // Basic Information Tab
        ->tab('basic', 'Basic Information', 'fa fa-info-circle')
            ->text('name')
                ->required()
                ->searchable()
                ->sortable()
                ->rules(['max:255'])
            ->textarea('description')
                ->searchable()
                ->rules(['max:1000'])
            ->select('category_id')
                ->required()
                ->searchable()
                ->sortable()
                ->options($this->getCategoryOptions())
                ->rules(['exists:categories,id'])
            ->divider('Product Guidelines')
            ->alert('Product names should be descriptive and unique.', 'info')
            ->end()

        // Pricing Tab
        ->tab('pricing', 'Pricing & Inventory', 'fa fa-dollar-sign')
            ->number('price')
                ->required()
                ->searchable()
                ->sortable()
                ->rules(['numeric', 'min:0'])
            ->number('cost')
                ->searchable()
                ->sortable()
                ->rules(['numeric', 'min:0'])
            ->number('stock_quantity')
                ->required()
                ->searchable()
                ->sortable()
                ->rules(['integer', 'min:0'])
            ->select('currency')
                ->required()
                ->options([
                    'USD' => 'US Dollar',
                    'EUR' => 'Euro',
                    'GBP' => 'British Pound'
                ])
                ->rules(['in:USD,EUR,GBP'])
            ->divider('Pricing Information')
            ->customHtml(
                '<div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <h4 class="font-medium text-green-800 mb-2">Pricing Guidelines:</h4>
                    <p class="text-sm text-green-700">Set competitive prices while maintaining healthy profit margins.</p>
                </div>',
                'Pricing Guidelines',
                'bg-green-50 border border-green-200 rounded-lg p-4'
            )
            ->end()

        // Media Tab
        ->tab('media', 'Media & Images', 'fa fa-image')
            ->file('main_image')
                ->rules(['image', 'max:2048'])
            ->file('gallery')
                ->multiple()
                ->rules(['image', 'max:2048'])
            ->text('video_url')
                ->rules(['url'])
            ->divider('Media Guidelines')
            ->alert('Images should be high quality and properly sized.', 'warning')
            ->end()

        // SEO Tab
        ->tab('seo', 'SEO & Meta', 'fa fa-search')
            ->text('meta_title')
                ->rules(['max:60'])
            ->textarea('meta_description')
                ->rules(['max:160'])
            ->text('slug')
                ->searchable()
                ->rules(['max:255', 'unique:products,slug'])
            ->text('keywords')
                ->rules(['max:255'])
            ->divider('SEO Best Practices')
            ->alert('Meta descriptions should be compelling and under 160 characters.', 'info')
            ->end()

        // Advanced Settings Tab
        ->tab('advanced', 'Advanced Settings', 'fa fa-cog')
            ->checkbox('is_featured')
                ->searchable()
                ->sortable()
            ->checkbox('is_active')
                ->searchable()
                ->sortable()
            ->select('status')
                ->options([
                    'draft' => 'Draft',
                    'published' => 'Published',
                    'archived' => 'Archived'
                ])
                ->rules(['in:draft,published,archived'])
            ->datetime('publish_date')
                ->rules(['date'])
            ->divider('Advanced Options')
            ->customHtml(
                '<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <h4 class="font-medium text-yellow-800 mb-2">Advanced Features:</h4>
                    <p class="text-sm text-yellow-700">These settings affect how your product appears and behaves in the system.</p>
                </div>',
                'Advanced Features',
                'bg-yellow-50 border border-yellow-200 rounded-lg p-4'
            )
            ->end();
}
```

## Tab Best Practices

### 1. Logical Organization

Group related fields together in meaningful tabs:
- **Basic Information**: Core fields like name, description, category
- **Details**: Extended information like specifications, features
- **Media**: Images, videos, documents
- **Pricing**: Cost, price, inventory
- **SEO**: Meta tags, keywords, URLs
- **Settings**: Status, visibility, advanced options

### 2. Tab Naming

Use clear, descriptive names for tabs:
- ✅ Good: "Basic Information", "Contact Details", "Payment Settings"
- ❌ Bad: "Tab 1", "Stuff", "More"

### 3. Icon Usage

Use appropriate FontAwesome icons to make tabs more intuitive:
- `fa fa-user` for user information
- `fa fa-cog` for settings
- `fa fa-image` for media
- `fa fa-dollar-sign` for pricing
- `fa fa-search` for SEO

### 4. Content Balance

Don't overload tabs with too many fields. Aim for 5-10 fields per tab for optimal user experience.

### 5. Progressive Disclosure

Use tabs to implement progressive disclosure:
- Start with essential information in the first tab
- Put optional or advanced settings in later tabs
- Use the last tab for system settings or advanced options

### 6. Method Chaining

Take advantage of method chaining for cleaner code:

```php
->tab('profile', 'Profile Details')
    ->text('first_name')->required()->searchable()->sortable()
    ->text('last_name')->required()->searchable()->sortable()
    ->email('email')->required()->searchable()->sortable()
    ->divider('Contact Information')
    ->text('phone')->searchable()->rules(['max:20'])
    ->end()
```

## Tab Styling

The tab system automatically includes:
- Responsive tab navigation
- Active tab highlighting
- Smooth transitions between tabs
- Mobile-friendly design
- Consistent styling with the UserPanel theme

## Troubleshooting

### Common Issues

1. **Tabs not showing**: Make sure you called `->enableTabs()`
2. **Fields not appearing**: Ensure you called `->end()` after each tab
3. **Styling issues**: Check that Alpine.js is included in your layout
4. **Method chaining errors**: Remember that field methods return the TabBuilder, allowing you to chain multiple methods

### Validation

Tabs work seamlessly with Laravel validation:
- All field rules are applied regardless of which tab they're in
- Validation errors are displayed on the appropriate tab
- Form submission includes all fields from all tabs

## Advanced Features

### Conditional Tabs

You can conditionally show/hide tabs based on certain conditions:

```php
if ($user->isAdmin()) {
    $resource->tab('admin', 'Admin Settings', 'fa fa-shield')
        ->text('admin_level')
        ->end();
}
```

### Dynamic Tab Content

Generate tab content dynamically:

```php
foreach ($categories as $category) {
    $resource->tab("category_{$category->id}", $category->name, 'fa fa-folder')
        ->text("field_{$category->id}")
        ->end();
}
```

### Tab Permissions

Control tab access based on user permissions:

```php
if (auth()->user()->can('manage_pricing')) {
    $resource->tab('pricing', 'Pricing', 'fa fa-dollar-sign')
        ->number('price')
        ->end();
}
```

## Conclusion

The tab system in UserPanel provides a powerful way to organize complex forms into logical, user-friendly sections. By following these guidelines and examples, you can create forms that are both functional and easy to use.

Remember:
- Always call `->enableTabs()` to activate the tab system
- Use `->end()` to close each tab and return to the ResourceService
- Take advantage of method chaining for cleaner code
- Organize fields logically across tabs
- Use appropriate icons and labels
- Keep tabs balanced in terms of content
- Test your forms thoroughly to ensure all tabs work correctly
