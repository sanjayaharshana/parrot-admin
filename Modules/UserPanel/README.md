# UserPanel Module

## Sidebar Control

The UserPanel module automatically includes controllers with `index` methods in the sidebar navigation. To control which controllers appear in the sidebar, you can use the `showInSidebar` property.

### How to exclude a controller from sidebar:

Add the `showInSidebar` property to your controller and set it to `false`:

```php
<?php

namespace Modules\UserPanel\Http\Controllers;

use Modules\UserPanel\Http\Base\BaseController;

class YourController extends BaseController
{
    // Set to false to exclude from sidebar
    public $showInSidebar = false;
    
    public function index()
    {
        // Your index method
    }
}
```

### Default Behavior:

- Controllers that extend `BaseController` have `showInSidebar = true` by default
- Only controllers with `index` methods are considered for sidebar inclusion
- Only controllers in the `Modules\UserPanel` namespace are included

### Example:

The `TestController` is excluded from the sidebar because it has `showInSidebar = false`, while `UserPanelController` appears in the sidebar because it uses the default value of `true`.

## Enhanced Form Service

The module includes an enhanced `FormService` class for creating beautiful, modern backend forms with Tailwind CSS styling:

### Basic Usage:

```php
use Modules\UserPanel\Services\FormService;

$form = new FormService();

// Text field with label and placeholder
$form->text()
    ->name('username')
    ->label('Username')
    ->placeholder('Enter your username')
    ->value('john_doe')
    ->required();

// Email field
$form->email()
    ->name('email')
    ->label('Email Address')
    ->placeholder('Enter your email')
    ->value('john@example.com')
    ->required();

// Textarea field
$form->textarea()
    ->name('description')
    ->label('Description')
    ->placeholder('Enter your description')
    ->value('This is a sample description')
    ->required();

// Select dropdown
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

// Number field
$form->number()
    ->name('age')
    ->label('Age')
    ->placeholder('Enter your age')
    ->value('25')
    ->required();

// Checkbox
$form->checkbox()
    ->name('newsletter')
    ->label('Subscribe to newsletter')
    ->value('1');

// Radio buttons
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

$html = $form->renderForm();
```

### Available Field Types:

- `text()` - Text input
- `email()` - Email input
- `password()` - Password input
- `number()` - Number input
- `textarea()` - Textarea
- `select()` - Dropdown select
- `checkbox()` - Checkbox
- `radio()` - Radio buttons

### Field Methods:

- `name(string $name)` - Set field name
- `value(string $value)` - Set field value
- `label(string $label)` - Set field label
- `placeholder(string $placeholder)` - Set placeholder text
- `required(bool $required = true)` - Make field required
- `options(array $options)` - Set options for select/radio fields
- `attribute(string $key, string $value)` - Add custom HTML attributes

### Features:

- **Modern Styling**: Beautiful Tailwind CSS styling with gradients and animations
- **Responsive Design**: Works perfectly on all screen sizes
- **Accessibility**: Proper labels, focus states, and semantic HTML
- **Validation Ready**: Required fields are marked with asterisks
- **Customizable**: Easy to extend and customize styling
- **Type Safety**: Fluent interface with method chaining 