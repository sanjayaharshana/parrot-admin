# Field-Level Validation System Examples

## Overview

This document demonstrates how to use the new field-level validation system in FormService, which allows you to define validation rules directly on individual fields for a more intuitive and user-friendly approach.

## New Field-Level Validation Methods

### Basic Validation Rules

```php
// Single rule
$form->text()
    ->name('title')
    ->label('Title')
    ->rule('required')
    ->rule('string')
    ->rule('max:255');

// Multiple rules at once
$form->text()
    ->name('email')
    ->label('Email')
    ->rules(['required', 'email', 'unique:users,email']);

// With custom messages
$form->text()
    ->name('username')
    ->label('Username')
    ->rule('required')
    ->rule('string')
    ->rule('min:3')
    ->rule('unique:users,username')
    ->message('required', 'Please enter a username.')
    ->message('min', 'Username must be at least 3 characters.')
    ->message('unique', 'This username is already taken.');
```

### Complete Form Example

```php
public function createForm($mode = 'create')
{
    $layout = $this->layoutService;
    $layout->setFormService($this->form);
    
    $layoutRow = $layout->row();
    $layoutRow->column(6, function ($form, $column) {
        $column->addField(
            $form->text()
                ->name('title')
                ->label('Title')
                ->placeholder('Enter title')
                ->rule('required')
                ->rule('string')
                ->rule('max:255')
                ->message('required', 'Please enter a title.')
                ->message('max', 'Title must not exceed 255 characters.')
                ->required()
        );
        
        $column->addField(
            $form->textarea()
                ->name('desc')
                ->label('Description')
                ->placeholder('Enter description')
                ->rule('required')
                ->rule('string')
                ->message('required', 'Please enter a description.')
                ->required()
        );
    });
    
    $layoutRow->column(6, function ($form, $column) {
        $column->addField(
            $form->select()
                ->name('uploader_id')
                ->label('Uploader')
                ->options(function () {
                    return User::all()->pluck('name', 'id')->toArray();
                })
                ->rule('required')
                ->rule('exists:users,id')
                ->message('required', 'Please select an uploader.')
                ->message('exists', 'The selected uploader is invalid.')
                ->required()
        );

        $column->addField(
            $form->text()
                ->name('path')
                ->label('Path')
                ->placeholder('Enter file path')
                ->rule('nullable')
                ->rule('string')
                ->rule('max:255')
        );
    });
    
    return $layout->render();
}
```

## Validation Methods

### Field-Level Methods

| Method | Description | Example |
|--------|-------------|---------|
| `rule(string $rule)` | Add a single validation rule | `->rule('required')` |
| `rules(array $rules)` | Add multiple validation rules | `->rules(['required', 'email'])` |
| `message(string $rule, string $message)` | Add custom validation message | `->message('required', 'Please enter a value.')` |
| `messages(array $messages)` | Add multiple validation messages | `->messages(['required' => 'Required', 'email' => 'Invalid email'])` |
| `required(bool $required = true)` | Mark field as required (auto-adds required rule) | `->required()` |

### Common Validation Rules

```php
// Required fields
->rule('required')

// String validation
->rule('string')
->rule('max:255')
->rule('min:3')

// Email validation
->rule('email')
->rule('unique:users,email')

// Numeric validation
->rule('numeric')
->rule('integer')
->rule('between:1,100')

// Date validation
->rule('date')
->rule('date_format:Y-m-d')
->rule('after:today')

// File validation
->rule('file')
->rule('image')
->rule('mimes:jpeg,png,jpg')
->rule('max:2048')

// Conditional validation
->rule('nullable')
->rule('sometimes')
->rule('required_if:other_field,value')
```

## Advanced Examples

### Conditional Validation

```php
$form->text()
    ->name('phone')
    ->label('Phone Number')
    ->rule('nullable')
    ->rule('string')
    ->rule('regex:/^[0-9\-\+\(\)\s]+$/')
    ->message('regex', 'Please enter a valid phone number.');
```

### Unique Validation with Ignore

```php
// For create forms
$form->text()
    ->name('email')
    ->label('Email')
    ->rule('required')
    ->rule('email')
    ->rule('unique:users,email');

// For edit forms (ignore current record)
$form->text()
    ->name('email')
    ->label('Email')
    ->rule('required')
    ->rule('email')
    ->rule('unique:users,email,' . $this->model->id);
```

### Complex Validation Rules

```php
$form->text()
    ->name('password')
    ->label('Password')
    ->rule('required')
    ->rule('string')
    ->rule('min:8')
    ->rule('regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/')
    ->message('required', 'Please enter a password.')
    ->message('min', 'Password must be at least 8 characters.')
    ->message('regex', 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.');
```

### File Upload Validation

```php
$form->file()
    ->name('avatar')
    ->label('Profile Picture')
    ->rule('nullable')
    ->rule('file')
    ->rule('image')
    ->rule('mimes:jpeg,png,jpg')
    ->rule('max:2048')
    ->message('file', 'Please select a valid file.')
    ->message('image', 'The file must be an image.')
    ->message('mimes', 'The file must be a JPEG, PNG, or JPG image.')
    ->message('max', 'The image must not be larger than 2MB.');
```

## Benefits

1. **Intuitive API**: Validation rules are defined directly on fields
2. **Better Readability**: Code is more self-documenting
3. **Easier Maintenance**: Validation logic is co-located with field definitions
4. **Type Safety**: IDE autocomplete support for validation methods
5. **Consistent Validation**: All fields use the same validation system
6. **User-Friendly**: Custom validation messages provide better UX

## Migration from Old System

### Before (FormService-level validation)

```php
// Set validation rules separately
$this->form->setValidationRules([
    'title' => 'required|string|max:255',
    'desc' => 'required|string',
    'uploader_id' => 'required|exists:users,id',
    'path' => 'nullable|string|max:255'
]);

// Set validation messages separately
$this->form->setValidationMessages([
    'title.required' => 'Please enter a title.',
    'title.max' => 'Title must not exceed 255 characters.',
    'desc.required' => 'Please enter a description.',
    'uploader_id.required' => 'Please select an uploader.',
    'uploader_id.exists' => 'The selected uploader is invalid.'
]);

// Add fields without validation
$column->addField(
    $form->text()
        ->name('title')
        ->label('Title')
        ->placeholder('Enter title')
        ->required()
);
```

### After (Field-level validation)

```php
// Validation rules defined directly on fields
$column->addField(
    $form->text()
        ->name('title')
        ->label('Title')
        ->placeholder('Enter title')
        ->rule('required')
        ->rule('string')
        ->rule('max:255')
        ->message('required', 'Please enter a title.')
        ->message('max', 'Title must not exceed 255 characters.')
        ->required()
);
```

## Best Practices

1. **Define validation rules on fields**: Keep validation logic with field definitions
2. **Use descriptive messages**: Provide user-friendly error messages
3. **Group related rules**: Use `rules()` method for multiple related rules
4. **Test validation thoroughly**: Ensure all edge cases are covered
5. **Document complex rules**: Add comments for complex validation logic
6. **Use consistent naming**: Follow Laravel naming conventions for validation rules 