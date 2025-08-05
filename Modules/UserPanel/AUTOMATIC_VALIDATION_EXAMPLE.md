# Automatic Validation System Examples

## Overview

The FormService now includes an **automatic validation system** that intelligently adds validation rules based on field type and attributes. This makes form creation much more intuitive and reduces boilerplate code.

## Automatic Validation Rules

### Field Type-Based Rules

The system automatically adds validation rules based on the field type:

| Field Type | Automatic Rules | Description |
|------------|----------------|-------------|
| `text()` | `string` | Automatically validates as string |
| `textarea()` | `string` | Automatically validates as string |
| `email()` | `email` | Automatically validates as email |
| `number()` | `numeric` | Automatically validates as numeric |
| `file()` | `file` | Automatically validates as file |
| `password()` | `string` | Automatically validates as string |

### Required Field Rules

When you call `->required()`, the system automatically adds:
- `required` validation rule
- Type-specific validation rules (e.g., `string` for text fields)

## Examples

### Basic Automatic Validation

```php
// Text field - automatically gets 'string' validation
$form->text()
    ->name('title')
    ->label('Title')
    ->required(); // Automatically adds 'required' and 'string' rules

// Email field - automatically gets 'email' validation
$form->email()
    ->name('email')
    ->label('Email')
    ->required(); // Automatically adds 'required' and 'email' rules

// Number field - automatically gets 'numeric' validation
$form->number()
    ->name('age')
    ->label('Age')
    ->required(); // Automatically adds 'required' and 'numeric' rules

// File field - automatically gets 'file' validation
$form->file()
    ->name('avatar')
    ->label('Avatar')
    ->required(); // Automatically adds 'required' and 'file' rules
```

### Complete Form with Automatic Validation

```php
public function createForm($mode = 'create')
{
    $layout = $this->layoutService;
    $layout->setFormService($this->form);
    
    $layoutRow = $layout->row();
    $layoutRow->column(6, function ($form, $column) {
        // Title - automatically gets 'string' validation
        $column->addField(
            $form->text()
                ->name('title')
                ->label('Title')
                ->placeholder('Enter title')
                ->rule('max:255') // Additional custom rule
                ->message('required', 'Please enter a title.')
                ->message('max', 'Title must not exceed 255 characters.')
                ->required() // Automatically adds 'required' and 'string' rules
        );
        
        // Description - automatically gets 'string' validation
        $column->addField(
            $form->textarea()
                ->name('desc')
                ->label('Description')
                ->placeholder('Enter description')
                ->message('required', 'Please enter a description.')
                ->required() // Automatically adds 'required' and 'string' rules
        );
    });
    
    $layoutRow->column(6, function ($form, $column) {
        // Email - automatically gets 'email' validation
        $column->addField(
            $form->email()
                ->name('email')
                ->label('Email')
                ->placeholder('Enter email')
                ->message('required', 'Please enter an email address.')
                ->message('email', 'Please enter a valid email address.')
                ->required() // Automatically adds 'required' and 'email' rules
        );

        // File upload - automatically gets 'file' validation
        $column->addField(
            $form->file()
                ->name('document')
                ->label('Document')
                ->accept('.pdf,.doc,.docx')
                ->message('required', 'Please select a document.')
                ->required() // Automatically adds 'required' and 'file' rules
        );
    });
    
    return $layout->render();
}
```

## Custom Rule Classes

You can also use custom Laravel validation rule classes:

```php
use App\Rules\CustomValidationRule;

// Custom rule class
$form->text()
    ->name('username')
    ->label('Username')
    ->rule(new CustomValidationRule())
    ->message('custom', 'Custom validation failed.')
    ->required();
```

## Advanced Examples

### Conditional Validation

```php
$form->text()
    ->name('phone')
    ->label('Phone Number')
    ->rule('nullable')
    ->rule('regex:/^[0-9\-\+\(\)\s]+$/')
    ->message('regex', 'Please enter a valid phone number.');
```

### File Validation with Automatic Type Detection

```php
$form->file()
    ->name('avatar')
    ->label('Profile Picture')
    ->accept('image/*') // Automatically adds 'image' validation
    ->rule('max:2048') // 2MB max
    ->message('required', 'Please select a profile picture.')
    ->message('image', 'The file must be an image.')
    ->message('max', 'The image must not be larger than 2MB.')
    ->required();
```

### Numeric Validation with Range

```php
$form->number()
    ->name('age')
    ->label('Age')
    ->rule('between:1,120')
    ->message('required', 'Please enter your age.')
    ->message('between', 'Age must be between 1 and 120.')
    ->required();
```

## Validation Rules Applied Automatically

### Text Fields (`text()`, `textarea()`)
- `string` - Validates as string
- `max:255` - If maxlength attribute is set
- `min:3` - If minlength attribute is set

### Email Fields (`email()`)
- `email` - Validates as email format

### Number Fields (`number()`)
- `numeric` - Validates as numeric

### File Fields (`file()`)
- `file` - Validates as file upload
- `image` - If accept attribute contains 'image/*'
- `mimes:jpeg,png,jpg` - If accept attribute contains file extensions

### Password Fields (`password()`)
- `string` - Validates as string

## Benefits

1. **Reduced Boilerplate**: No need to manually add common validation rules
2. **Intuitive API**: Validation rules are automatically applied based on field type
3. **Consistent Validation**: All fields of the same type get consistent validation
4. **Easy to Extend**: Custom rules can still be added manually
5. **Type Safety**: IDE autocomplete support for validation methods
6. **User-Friendly**: Automatic validation messages for common rules

## Migration from Manual Validation

### Before (Manual validation)

```php
$form->text()
    ->name('title')
    ->label('Title')
    ->rule('required')
    ->rule('string')
    ->rule('max:255')
    ->required();
```

### After (Automatic validation)

```php
$form->text()
    ->name('title')
    ->label('Title')
    ->rule('max:255') // Only custom rules needed
    ->required(); // Automatically adds 'required' and 'string' rules
```

## Best Practices

1. **Use automatic validation**: Let the system handle common validation rules
2. **Add custom rules only**: Only specify rules that aren't automatically applied
3. **Use descriptive messages**: Provide user-friendly error messages
4. **Test thoroughly**: Ensure all validation scenarios are covered
5. **Document custom rules**: Add comments for complex validation logic
6. **Use consistent naming**: Follow Laravel naming conventions for validation rules 