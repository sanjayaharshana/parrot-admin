# FormService Documentation

## Overview

The FormService is a powerful backend-driven form builder for the UserPanel module that allows you to create beautiful, responsive forms programmatically using PHP. It supports advanced layout management, multiple field types, and modern Tailwind CSS styling.

## Table of Contents

1. [Quick Start](#quick-start)
2. [Basic Usage](#basic-usage)
3. [Field Types](#field-types)
4. [Layout Management](#layout-management)
5. [Advanced Features](#advanced-features)
6. [Best Practices](#best-practices)
7. [Examples](#examples)
8. [API Reference](#api-reference)

## Quick Start

### Installation

The FormService is included in the UserPanel module. No additional installation required.

### Basic Example

```php
use Modules\UserPanel\Services\FormService;

$form = new FormService();

$form->text()
    ->name('username')
    ->label('Username')
    ->placeholder('Enter your username')
    ->required();

$html = $form->renderForm();
```

## Basic Usage

### Creating a Form

```php
use Modules\UserPanel\Services\FormService;

$form = new FormService();
```

### Adding Fields

```php
// Text field
$form->text()
    ->name('first_name')
    ->label('First Name')
    ->placeholder('Enter your first name')
    ->value('John')
    ->required();

// Email field
$form->email()
    ->name('email')
    ->label('Email Address')
    ->placeholder('Enter your email')
    ->required();
```

### Rendering the Form

```php
$html = $form->renderForm();
```

## Field Types

### Text Input

```php
$form->text()
    ->name('username')
    ->label('Username')
    ->placeholder('Enter username')
    ->value('john_doe')
    ->required();
```

### Email Input

```php
$form->email()
    ->name('email')
    ->label('Email Address')
    ->placeholder('Enter your email')
    ->required();
```

### Password Input

```php
$form->password()
    ->name('password')
    ->label('Password')
    ->placeholder('Enter your password')
    ->required();
```

### Number Input

```php
$form->number()
    ->name('age')
    ->label('Age')
    ->placeholder('Enter your age')
    ->value('25')
    ->required();
```

### Textarea

```php
$form->textarea()
    ->name('bio')
    ->label('Biography')
    ->placeholder('Tell us about yourself')
    ->value('A passionate developer...')
    ->required();
```

### Select Dropdown

```php
$form->select()
    ->name('country')
    ->label('Country')
    ->options([
        'us' => 'United States',
        'uk' => 'United Kingdom',
        'ca' => 'Canada',
        'au' => 'Australia'
    ])
    ->value('us')
    ->required();
```

### Checkbox

```php
$form->checkbox()
    ->name('newsletter')
    ->label('Subscribe to newsletter')
    ->value('1');
```

### Radio Buttons

```php
$form->radio()
    ->name('gender')
    ->label('Gender')
    ->options([
        'male' => 'Male',
        'female' => 'Female',
        'other' => 'Other'
    ])
    ->value('male')
    ->required();
```

## Layout Management

### Rows and Columns

Create responsive layouts using rows and columns:

```php
// Create a row with two equal columns
$row = $form->row();
$column1 = $row->column(6); // 50% width
$column2 = $row->column(6); // 50% width

// Add fields to columns
$column1->addField(
    $form->text()
        ->name('first_name')
        ->label('First Name')
        ->required()
);

$column2->addField(
    $form->text()
        ->name('last_name')
        ->label('Last Name')
        ->required()
);
```

### Grid Layout

Create CSS Grid layouts:

```php
// 3-column grid
$grid = $form->grid(3, 4); // 3 columns, gap-4

$item1 = $grid->item();
$item1->addField(
    $form->text()
        ->name('street')
        ->label('Street')
        ->required()
);

$item2 = $grid->item();
$item2->addField(
    $form->text()
        ->name('city')
        ->label('City')
        ->required()
);

$item3 = $grid->item();
$item3->addField(
    $form->text()
        ->name('zip')
        ->label('ZIP Code')
        ->required()
);
```

### Sections

Group related fields with titles and descriptions:

```php
$section = $form->section('Personal Information', 'Please provide your basic details.');

$section->addField(
    $form->text()
        ->name('first_name')
        ->label('First Name')
        ->required()
);

$section->addField(
    $form->text()
        ->name('last_name')
        ->label('Last Name')
        ->required()
);

$section->addField(
    $form->email()
        ->name('email')
        ->label('Email')
        ->required()
);
```

### Cards

Create contained content areas:

```php
$card = $form->card('Contact Information');

$card->addField(
    $form->text()
        ->name('phone')
        ->label('Phone Number')
);

$card->addField(
    $form->text()
        ->name('website')
        ->label('Website')
);

$card->addField(
    $form->textarea()
        ->name('notes')
        ->label('Notes')
);
```

## Advanced Features

### Custom Attributes

Add custom HTML attributes to fields:

```php
$form->text()
    ->name('username')
    ->label('Username')
    ->attribute('data-validation', 'required')
    ->attribute('maxlength', '50')
    ->required();
```

### Conditional Fields

You can conditionally add fields based on logic:

```php
$form->text()
    ->name('company')
    ->label('Company Name');

if ($user->isBusiness()) {
    $form->text()
        ->name('tax_id')
        ->label('Tax ID')
        ->required();
}
```

### Dynamic Options

Generate select options dynamically:

```php
$countries = Country::all()->pluck('name', 'code')->toArray();

$form->select()
    ->name('country')
    ->label('Country')
    ->options($countries)
    ->required();
```

## Best Practices

### 1. Field Naming

Use descriptive, consistent field names:

```php
// Good
$form->text()->name('user_first_name')->label('First Name');
$form->text()->name('user_last_name')->label('Last Name');

// Avoid
$form->text()->name('fname')->label('First Name');
$form->text()->name('lname')->label('Last Name');
```

### 2. Validation

Always mark required fields:

```php
$form->text()
    ->name('email')
    ->label('Email Address')
    ->required(); // Shows red asterisk
```

### 3. Layout Organization

Group related fields logically:

```php
// Personal Information
$section = $form->section('Personal Information');
$section->addField($form->text()->name('first_name')->label('First Name'));
$section->addField($form->text()->name('last_name')->label('Last Name'));

// Contact Information
$section = $form->section('Contact Information');
$section->addField($form->email()->name('email')->label('Email'));
$section->addField($form->text()->name('phone')->label('Phone'));
```

### 4. Responsive Design

Use appropriate column widths:

```php
// Mobile-friendly: Stack on small screens
$row = $form->row();
$row->column(12)->addField($form->text()->name('full_name')); // Full width on mobile

// Desktop-friendly: Side by side
$row = $form->row();
$row->column(6)->addField($form->text()->name('first_name')); // Half width on desktop
$row->column(6)->addField($form->text()->name('last_name'));
```

## Examples

### Complete User Registration Form

```php
use Modules\UserPanel\Services\FormService;

$form = new FormService();

// Personal Information Section
$section = $form->section('Personal Information', 'Please provide your basic details.');
$section->addField(
    $form->text()
        ->name('first_name')
        ->label('First Name')
        ->placeholder('Enter your first name')
        ->required()
);
$section->addField(
    $form->text()
        ->name('last_name')
        ->label('Last Name')
        ->placeholder('Enter your last name')
        ->required()
);
$section->addField(
    $form->email()
        ->name('email')
        ->label('Email Address')
        ->placeholder('Enter your email')
        ->required()
);

// Contact Information Row
$row = $form->row();
$row->column(6)->addField(
    $form->text()
        ->name('phone')
        ->label('Phone Number')
        ->placeholder('Enter your phone number')
        ->required()
);
$row->column(6)->addField(
    $form->text()
        ->name('website')
        ->label('Website')
        ->placeholder('Enter your website URL')
);

// Address Grid
$grid = $form->grid(3, 4);
$grid->item()->addField(
    $form->text()
        ->name('street')
        ->label('Street Address')
        ->placeholder('Enter street address')
        ->required()
);
$grid->item()->addField(
    $form->text()
        ->name('city')
        ->label('City')
        ->placeholder('Enter city')
        ->required()
);
$grid->item()->addField(
    $form->text()
        ->name('zip_code')
        ->label('ZIP Code')
        ->placeholder('Enter ZIP code')
        ->required()
);

// Profile Card
$card = $form->card('Profile Information');
$card->addField(
    $form->textarea()
        ->name('bio')
        ->label('Biography')
        ->placeholder('Tell us about yourself')
);
$card->addField(
    $form->select()
        ->name('experience_level')
        ->label('Experience Level')
        ->options([
            'beginner' => 'Beginner',
            'intermediate' => 'Intermediate',
            'advanced' => 'Advanced',
            'expert' => 'Expert'
        ])
        ->required()
);

// Preferences Card
$card = $form->card('Preferences');
$card->addField(
    $form->select()
        ->name('country')
        ->label('Country')
        ->options([
            'us' => 'United States',
            'uk' => 'United Kingdom',
            'ca' => 'Canada',
            'au' => 'Australia'
        ])
        ->required()
);
$card->addField(
    $form->number()
        ->name('age')
        ->label('Age')
        ->placeholder('Enter your age')
        ->required()
);
$card->addField(
    $form->checkbox()
        ->name('newsletter')
        ->label('Subscribe to newsletter')
        ->value('1')
);
$card->addField(
    $form->radio()
        ->name('gender')
        ->label('Gender')
        ->options([
            'male' => 'Male',
            'female' => 'Female',
            'other' => 'Other'
        ])
        ->required()
);

$html = $form->renderForm();
```

### Simple Contact Form

```php
$form = new FormService();

$form->text()
    ->name('name')
    ->label('Full Name')
    ->placeholder('Enter your full name')
    ->required();

$form->email()
    ->name('email')
    ->label('Email Address')
    ->placeholder('Enter your email')
    ->required();

$form->textarea()
    ->name('message')
    ->label('Message')
    ->placeholder('Enter your message')
    ->required();

$html = $form->renderForm();
```

## API Reference

### FormService Methods

| Method | Description | Parameters | Returns |
|--------|-------------|------------|---------|
| `text()` | Create text input field | None | Field |
| `email()` | Create email input field | None | Field |
| `password()` | Create password input field | None | Field |
| `number()` | Create number input field | None | Field |
| `textarea()` | Create textarea field | None | Field |
| `select()` | Create select dropdown | None | Field |
| `checkbox()` | Create checkbox field | None | Field |
| `radio()` | Create radio button group | None | Field |
| `row()` | Create row container | None | Row |
| `column(int $width)` | Create column container | `$width` (1-12) | Column |
| `grid(int $cols, int $gap)` | Create grid container | `$cols` (1-6), `$gap` (1-12) | Grid |
| `section(string $title, string $description)` | Create section | `$title`, `$description` | Section |
| `card(string $title)` | Create card container | `$title` | Card |
| `renderForm()` | Render complete form HTML | None | string |

### Field Methods

| Method | Description | Parameters | Returns |
|--------|-------------|------------|---------|
| `name(string $name)` | Set field name | `$name` | self |
| `label(string $label)` | Set field label | `$label` | self |
| `value(string $value)` | Set field value | `$value` | self |
| `placeholder(string $placeholder)` | Set placeholder text | `$placeholder` | self |
| `required(bool $required)` | Make field required | `$required` (default: true) | self |
| `options(array $options)` | Set options for select/radio | `$options` | self |
| `attribute(string $key, string $value)` | Add custom attribute | `$key`, `$value` | self |

### Layout Methods

| Method | Description | Parameters | Returns |
|--------|-------------|------------|---------|
| `addField(Field $field)` | Add field to layout | `$field` | self |
| `render()` | Render layout HTML | None | string |

## Styling

The FormService uses Tailwind CSS for styling and includes:

- **Modern Design**: Clean, professional appearance
- **Responsive Layout**: Works on all screen sizes
- **Focus States**: Blue ring effects on focus
- **Hover Effects**: Smooth transitions and animations
- **Validation Indicators**: Red asterisks for required fields
- **Accessibility**: Proper labels and semantic HTML

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## Troubleshooting

### Common Issues

1. **"Call to undefined method" error**
   - Ensure you're calling field methods on the FormService, not layout classes
   - Use `$form->text()` not `$column->text()`

2. **Fields not appearing**
   - Make sure to call `addField()` on layout components
   - Check that `renderForm()` is called at the end

3. **Styling issues**
   - Ensure Tailwind CSS is loaded
   - Check for CSS conflicts in your application

### Debug Tips

```php
// Debug form structure
$form = new FormService();
// ... add fields ...
dd($form); // Inspect form object

// Debug rendered HTML
$html = $form->renderForm();
dd($html); // Inspect generated HTML
```

## Contributing

When extending the FormService:

1. Follow the existing naming conventions
2. Add proper documentation for new methods
3. Include examples in this documentation
4. Test with different field types and layouts
5. Ensure responsive design compatibility

---

For more information, see the main UserPanel module documentation or contact the development team. 