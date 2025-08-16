# Conditional Field Display - Complete Guide

## Overview

The Conditional Field Display feature allows you to show/hide form fields based on the values of other fields. This creates dynamic, intelligent forms that adapt to user input, providing a better user experience and cleaner interfaces.

## Available Methods

### 1. **Show When Methods**

#### `->showWhen(string $fieldName, $value)`
Show a field when another field equals a specific value.

```php
->select('product_type')
    ->options(['physical' => 'Physical', 'digital' => 'Digital'])
->number('weight')
    ->showWhen('product_type', 'physical')  // Only show for physical products
```

#### `->showWhenIn(string $fieldName, array $values)`
Show a field when another field has any of the specified values.

```php
->select('category')
    ->options(['electronics', 'clothing', 'books'])
->text('warranty')
    ->showWhenIn('category', ['electronics', 'clothing'])  // Show for electronics and clothing
```

#### `->showWhenNotEmpty(string $fieldName)`
Show a field when another field is not empty.

```php
->text('phone')
->text('extension')
    ->showWhenNotEmpty('phone')  // Only show extension if phone is filled
```

### 2. **Hide When Methods**

#### `->hideWhen(string $fieldName, $value)`
Hide a field when another field equals a specific value.

```php
->select('product_type')
    ->options(['physical' => 'Physical', 'digital' => 'Digital'])
->text('shipping_class')
    ->hideWhen('product_type', 'digital')  // Hide for digital products
```

#### `->hideWhenIn(string $fieldName, array $values)`
Hide a field when another field has any of the specified values.

```php
->select('status')
    ->options(['active', 'inactive', 'draft'])
->text('publish_date')
    ->hideWhenIn('status', ['draft', 'inactive'])  // Hide for draft and inactive
```

#### `->hideWhenEmpty(string $fieldName)`
Hide a field when another field is empty.

```php
->text('email')
->text('email_confirm')
    ->hideWhenEmpty('email')  // Hide confirmation if email is empty
```

## Complete Implementation Examples

### **Example 1: Product Type-Based Fields**

```php
->tab('pricing', 'Pricing & Subscription', 'fa fa-dollar-sign')
    ->select('product_type')
        ->options([
            'physical' => 'Physical Product',
            'digital' => 'Digital Product',
            'subscription' => 'Subscription Product'
        ])
        ->help('Choose the type of product you are selling')
    
    // Physical product fields
    ->text('shipping_class')
        ->showWhen('product_type', 'physical')
        ->help('Shipping class for physical products')
    
    ->number('weight')
        ->showWhen('product_type', 'physical')
        ->help('Product weight in grams')
    
    // Digital product fields
    ->text('download_link')
        ->showWhen('product_type', 'digital')
        ->help('Direct download link')
    
    ->text('file_size')
        ->showWhen('product_type', 'digital')
        ->help('Size of the digital file')
    
    // Subscription fields
    ->number('subscription_price')
        ->showWhen('product_type', 'subscription')
        ->help('Monthly subscription price')
    
    ->select('subscription_interval')
        ->showWhen('product_type', 'subscription')
        ->help('Billing frequency')
    ->end()
```

### **Example 2: Conditional Validation Fields**

```php
->tab('settings', 'Settings', 'fa fa-cog')
    ->switch('requires_approval')
        ->label('Requires Approval')
        ->help('Enable approval workflow')
    
    ->select('approver_role')
        ->options(['admin' => 'Administrator', 'manager' => 'Manager'])
        ->showWhen('requires_approval', true)
        ->help('Role required for approval')
    
    ->text('approval_notes')
        ->showWhen('requires_approval', true)
        ->help('Notes for approvers')
    
    ->switch('auto_publish')
        ->label('Auto Publish')
        ->help('Publish automatically after approval')
        ->showWhen('requires_approval', true)
    ->end()
```

### **Example 3: Multi-Conditional Fields**

```php
->tab('advanced', 'Advanced', 'fa fa-cog')
    ->select('visibility')
        ->options(['public' => 'Public', 'private' => 'Private', 'restricted' => 'Restricted'])
    
    ->text('password')
        ->showWhen('visibility', 'restricted')
        ->help('Password for restricted access')
    
    ->select('access_level')
        ->options(['basic' => 'Basic', 'premium' => 'Premium', 'vip' => 'VIP'])
        ->showWhenIn('visibility', ['public', 'restricted'])
        ->help('User access level required')
    
    ->text('custom_message')
        ->showWhenNotEmpty('access_level')
        ->help('Custom message for users with this access level')
    ->end()
```

### **Example 4: Inventory Management**

```php
->tab('inventory', 'Inventory', 'fa fa-boxes')
    ->select('stock_management')
        ->options(['enabled' => 'Enabled', 'disabled' => 'Disabled'])
    
    ->number('stock_quantity')
        ->showWhen('stock_management', 'enabled')
        ->help('Current stock quantity')
    
    ->select('low_stock_threshold')
        ->options(['5' => '5 items', '10' => '10 items', '20' => '20 items'])
        ->showWhen('stock_management', 'enabled')
        ->help('Alert when stock falls below this level')
    
    ->switch('allow_backorder')
        ->showWhen('stock_management', 'enabled')
        ->label('Allow Backorders')
        ->help('Allow customers to order when out of stock')
    
    ->text('backorder_message')
        ->showWhen('allow_backorder', true)
        ->help('Message shown to customers during backorder')
    ->end()
```

## Advanced Usage Patterns

### **1. Chained Conditions**

```php
->select('user_type')
    ->options(['guest' => 'Guest', 'registered' => 'Registered', 'premium' => 'Premium'])

->text('company_name')
    ->showWhenIn('user_type', ['registered', 'premium'])
    ->help('Company name for business accounts')

->text('vat_number')
    ->showWhen('user_type', 'premium')
    ->showWhenNotEmpty('company_name')
    ->help('VAT number for premium business accounts')
```

### **2. Conditional Required Fields**

```php
->switch('has_warranty')
    ->label('Has Warranty')

->text('warranty_period')
    ->showWhen('has_warranty', true)
    ->required()  // Only required when warranty is enabled
    ->help('Warranty period (e.g., 1 year)')

->textarea('warranty_terms')
    ->showWhen('has_warranty', true)
    ->help('Warranty terms and conditions')
```

### **3. Dynamic Field Groups**

```php
->select('product_category')
    ->options(['electronics' => 'Electronics', 'clothing' => 'Clothing', 'books' => 'Books'])

// Electronics specific fields
->text('voltage')
    ->showWhen('product_category', 'electronics')
    ->help('Operating voltage')

->text('power_consumption')
    ->showWhen('product_category', 'electronics')
    ->help('Power consumption in watts')

// Clothing specific fields
->select('size')
    ->showWhen('product_category', 'clothing')
    ->options(['XS', 'S', 'M', 'L', 'XL', 'XXL'])
    ->help('Available sizes')

->select('color')
    ->showWhen('product_type', 'clothing')
    ->options(['red', 'blue', 'green', 'black', 'white'])
    ->help('Available colors')
```

## Best Practices

### **1. Logical Grouping**
- Group related conditional fields together
- Use clear, descriptive field names
- Provide helpful guidance text

### **2. Performance Considerations**
- Limit the number of conditional fields per form
- Use simple conditions when possible
- Avoid deeply nested conditional logic

### **3. User Experience**
- Always show the controlling field first
- Provide clear feedback about what fields are required
- Use progressive disclosure for complex forms

### **4. Validation**
- Ensure conditional fields have appropriate validation rules
- Consider cross-field validation requirements
- Provide clear error messages

## Technical Implementation

### **How It Works**

1. **Field Registration**: Fields register their conditional rules via `data-conditional` attributes
2. **JavaScript Initialization**: The system automatically initializes conditional field logic
3. **Event Listening**: Changes to controlling fields trigger visibility updates
4. **Dynamic Updates**: Fields show/hide based on current form state

### **Supported Field Types**

- **Text inputs**: `text`, `email`, `textarea`, `password`
- **Select dropdowns**: `select`
- **Checkboxes**: `checkbox`
- **Switches**: `switch`
- **Numbers**: `number`
- **Files**: `file`

### **Conditional Operators**

- **`equals`**: Exact value match
- **`in`**: Value is in array
- **`not_empty`**: Field has content
- **`empty`**: Field is empty

## Troubleshooting

### **Common Issues**

1. **Fields not showing/hiding**: Check field names match exactly
2. **JavaScript errors**: Ensure Alpine.js is loaded
3. **Performance issues**: Limit conditional field complexity

### **Debug Tips**

- Check browser console for JavaScript errors
- Verify field names in conditional rules
- Test with simple conditions first
- Use browser dev tools to inspect field attributes

## Conclusion

Conditional Field Display transforms static forms into dynamic, intelligent interfaces that adapt to user input. This feature significantly improves user experience by:

- **Reducing clutter**: Only show relevant fields
- **Improving flow**: Guide users through logical steps
- **Enhancing clarity**: Focus attention on important information
- **Increasing efficiency**: Streamline form completion

Use this feature thoughtfully to create forms that feel natural and intuitive for your users!
