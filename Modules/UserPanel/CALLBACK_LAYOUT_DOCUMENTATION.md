# Callback-Based Layout Documentation

## Overview

The callback-based layout syntax provides a much cleaner and more intuitive way to create form layouts. Instead of manually creating fields and then binding them to layout components, you can define everything inline within callbacks.

## Quick Start

```php
use Modules\UserPanel\Services\FormService;
use Modules\UserPanel\Services\LayoutService;

// Create services
$form = new FormService();
$layout = new LayoutService();
$layout->setFormService($form);

// Create layout with callbacks
$layout->column('1/2', function ($form, $column) {
    $column->addField(
        $form->text()
            ->name('title')
            ->label('Title')
            ->required()
    );
});

// Render the layout
echo $layout->render();
```

## Basic Syntax

### Column with Callback

```php
$layout->column('1/2', function ($form, $column) {
    // Add fields to this column
    $column->addField($form->text()->name('field1'));
    $column->addField($form->email()->name('field2'));
});
```

### Section with Callback

```php
$layout->section('Personal Info', 'Enter your details', function ($form, $section) {
    $section->addField($form->text()->name('first_name'));
    $section->addField($form->text()->name('last_name'));
});
```

### Card with Callback

```php
$layout->card('Additional Info', function ($form, $card) {
    $card->addField($form->textarea()->name('bio'));
    $card->addField($form->select()->name('country'));
});
```

### Grid with Callbacks

```php
$layout->grid(3, 4)
    ->item(function ($form, $item) {
        $item->addField($form->text()->name('field1'));
    })
    ->item(function ($form, $item) {
        $item->addField($form->text()->name('field2'));
    })
    ->item(function ($form, $item) {
        $item->addField($form->text()->name('field3'));
    });
```

## Width Support

The callback syntax supports flexible width definitions:

### Fractional Widths
```php
$layout->column('1/2');    // 50% width
$layout->column('1/3');    // 33.33% width
$layout->column('2/3');    // 66.66% width
$layout->column('1/4');    // 25% width
$layout->column('3/4');    // 75% width
```

### Numeric Widths
```php
$layout->column(6);        // 6/12 = 50% width
$layout->column(4);        // 4/12 = 33.33% width
$layout->column(8);        // 8/12 = 66.66% width
```

## Complete Example

```php
public function index()
{
    $form = new FormService();
    $layout = new LayoutService();
    $layout->setFormService($form);
    
    // The first column occupies 1/2 of the page width
    $layout->column('1/2', function ($form, $column) {
        $column->addField(
            $form->text()
                ->name('title')
                ->label('Title')
                ->placeholder('Enter title')
                ->required()
        );
        
        $column->addField(
            $form->textarea()
                ->name('desc')
                ->label('Description')
                ->placeholder('Enter description')
                ->required()
        );
        
        $column->addField(
            $form->select()
                ->name('uploader_id')
                ->label('Uploader')
                ->options([
                    1 => 'John Doe',
                    2 => 'Jane Smith',
                    3 => 'Bob Johnson'
                ])
                ->required()
        );
        
        $column->addField(
            $form->text()
                ->name('path')
                ->label('Path')
                ->placeholder('Enter file path')
                ->required()
        );
    });
    
    // The second column occupies 1/2 of the page width
    $layout->column('1/2', function ($form, $column) {
        $column->addField(
            $form->number()
                ->name('view_count')
                ->label('View Count')
                ->value('0')
        );
        
        $column->addField(
            $form->number()
                ->name('download_count')
                ->label('Download Count')
                ->value('0')
        );
        
        $column->addField(
            $form->number()
                ->name('rate')
                ->label('Rate')
                ->value('0')
        );
        
        $column->addField(
            $form->radio()
                ->name('privilege')
                ->label('Privilege')
                ->options([
                    1 => 'Public',
                    2 => 'Private',
                    3 => 'Restricted'
                ])
                ->value('1')
                ->required()
        );
        
        $column->addField(
            $form->text()
                ->name('created_at')
                ->label('Created At')
                ->placeholder('Select date')
        );
        
        $column->addField(
            $form->text()
                ->name('updated_at')
                ->label('Updated At')
                ->placeholder('Select date')
        );
    });
    
    return view('form', ['layout' => $layout->render()]);
}
```

## Advanced Patterns

### Nested Layouts

```php
// Row with columns using callbacks
$layout->row()
    ->column('1/3', function ($form, $column) {
        $column->addField($form->text()->name('phone'));
    })
    ->column('1/3', function ($form, $column) {
        $column->addField($form->text()->name('website'));
    })
    ->column('1/3', function ($form, $column) {
        $column->addField($form->text()->name('company'));
    });
```

### Mixed Approaches

You can mix callback-based and manual approaches:

```php
// Create a section with callback
$layout->section('Basic Info', function ($form, $section) {
    $section->addField($form->text()->name('name'));
    $section->addField($form->email()->name('email'));
});

// Add a divider
$layout->divider();

// Create a card manually
$card = $layout->card('Additional Info');
$card->addField($form->textarea()->name('bio'));
$card->addField($form->select()->name('country'));
```

## Benefits

### 1. Cleaner Syntax
- Much more readable and intuitive
- Everything is defined inline where it belongs
- No need to jump between field creation and layout binding

### 2. Automatic Binding
- Fields are automatically bound to their layout containers
- No need to manually call `addField()` after creating fields
- Reduces boilerplate code

### 3. Flexible Widths
- Support for fractional widths like '1/2', '1/3', '2/3'
- Makes responsive layouts much easier to create
- More intuitive than numeric grid systems

### 4. Better Organization
- Related fields are grouped together in callbacks
- Easier to understand the structure at a glance
- Better separation of concerns

## API Reference

### LayoutService Methods

#### `column($width, callable $callback = null)`
Creates a column with optional callback for field binding.

**Parameters:**
- `$width`: String ('1/2', '1/3') or integer (6, 4)
- `$callback`: Optional callback function with parameters ($form, $column)

#### `section($title, $description, callable $callback = null)`
Creates a section with title, description, and optional callback.

**Parameters:**
- `$title`: Section title
- `$description`: Section description
- `$callback`: Optional callback function with parameters ($form, $section)

#### `card($title, callable $callback = null)`
Creates a card with title and optional callback.

**Parameters:**
- `$title`: Card title
- `$callback`: Optional callback function with parameters ($form, $card)

#### `grid($cols, $gap)`
Creates a grid container.

**Parameters:**
- `$cols`: Number of columns
- `$gap`: Gap between items

#### `item(callable $callback = null)`
Adds an item to a grid with optional callback.

**Parameters:**
- `$callback`: Optional callback function with parameters ($form, $item)

### Callback Parameters

All callbacks receive two parameters:

1. **`$form`**: The FormService instance for creating fields
2. **`$container`**: The layout container (column, section, card, item) for adding fields

### Width Conversion

The system automatically converts fractional widths to Bootstrap-style grid classes:

- `'1/2'` → `w-full md:w-6/12` (50% on medium+ screens)
- `'1/3'` → `w-full md:w-4/12` (33.33% on medium+ screens)
- `'2/3'` → `w-full md:w-8/12` (66.66% on medium+ screens)
- `'1/4'` → `w-full md:w-3/12` (25% on medium+ screens)
- `'3/4'` → `w-full md:w-9/12` (75% on medium+ screens)

## Migration from Manual Approach

### Before (Manual)
```php
$layout = new LayoutService();
$form = new FormService();

$column = $layout->column(6);
$column->addField($form->text()->name('title'));
$column->addField($form->email()->name('email'));

$section = $layout->section('Info');
$section->addField($form->textarea()->name('bio'));
```

### After (Callback)
```php
$layout = new LayoutService();
$form = new FormService();
$layout->setFormService($form);

$layout->column('1/2', function ($form, $column) {
    $column->addField($form->text()->name('title'));
    $column->addField($form->email()->name('email'));
});

$layout->section('Info', function ($form, $section) {
    $section->addField($form->textarea()->name('bio'));
});
```

## Best Practices

1. **Set FormService**: Always call `setFormService()` before using callbacks
2. **Use Descriptive Widths**: Prefer fractional widths ('1/2') over numeric (6) for clarity
3. **Group Related Fields**: Use sections and cards to group related fields
4. **Keep Callbacks Focused**: Each callback should handle a logical group of fields
5. **Use Meaningful Names**: Give your layout containers meaningful titles and descriptions

## Examples

See the `CallbackLayoutController` for complete working examples:
- Basic example: `/callback-layout`
- Advanced example: `/callback-layout/advanced` 