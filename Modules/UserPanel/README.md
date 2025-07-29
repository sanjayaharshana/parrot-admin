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

## Enhanced Form Service with Layout Management

The module includes an enhanced `FormService` class for creating beautiful, modern backend forms with advanced layout management capabilities.

### Basic Usage:

```php
use Modules\UserPanel\Services\FormService;

$form = new FormService();

// Simple fields
$form->text()
    ->name('username')
    ->label('Username')
    ->placeholder('Enter your username')
    ->value('john_doe')
    ->required();

$html = $form->renderForm();
```

### Layout Management

The FormService now supports advanced layout management with rows, columns, grids, sections, and cards.

#### 1. Rows and Columns

```php
// Create a row with two equal columns
$form->row()
    ->column(6)  // 6/12 = 50% width
        ->text()
            ->name('first_name')
            ->label('First Name')
            ->required()
    ->column(6)  // 6/12 = 50% width
        ->text()
            ->name('last_name')
            ->label('Last Name')
            ->required();

// Different column widths
$form->row()
    ->column(4)  // 4/12 = 33% width
        ->text()
            ->name('phone')
            ->label('Phone')
    ->column(8)  // 8/12 = 67% width
        ->text()
            ->name('address')
            ->label('Address');
```

#### 2. Grid Layout

```php
// 3-column grid
$form->grid(3, 4)  // 3 columns, gap-4
    ->item()
        ->text()
            ->name('street')
            ->label('Street')
    ->item()
        ->text()
            ->name('city')
            ->label('City')
    ->item()
        ->text()
            ->name('zip')
            ->label('ZIP Code');

// 2-column grid
$form->grid(2, 6)  // 2 columns, gap-6
    ->item()
        ->email()
            ->name('email')
            ->label('Email')
    ->item()
        ->text()
            ->name('website')
            ->label('Website');
```

#### 3. Sections

```php
// Section with title and description
$form->section('Personal Information', 'Please provide your basic details.')
    ->text()
        ->name('first_name')
        ->label('First Name')
        ->required()
    ->text()
        ->name('last_name')
        ->label('Last Name')
        ->required()
    ->email()
        ->name('email')
        ->label('Email')
        ->required();
```

#### 4. Cards

```php
// Card with title
$form->card('Contact Information')
    ->text()
        ->name('phone')
        ->label('Phone Number')
    ->text()
        ->name('website')
        ->label('Website')
    ->textarea()
        ->name('notes')
        ->label('Notes');
```

### Complete Example

```php
$form = new FormService();

// Personal Information Section
$form->section('Personal Information', 'Please provide your basic personal details.')
    ->text()
        ->name('first_name')
        ->label('First Name')
        ->placeholder('Enter your first name')
        ->required()
    ->text()
        ->name('last_name')
        ->label('Last Name')
        ->placeholder('Enter your last name')
        ->required()
    ->email()
        ->name('email')
        ->label('Email Address')
        ->placeholder('Enter your email')
        ->required();

// Contact Information in a Row with Columns
$form->row()
    ->column(6)
        ->text()
            ->name('phone')
            ->label('Phone Number')
            ->placeholder('Enter your phone number')
            ->required()
    ->column(6)
        ->text()
            ->name('website')
            ->label('Website')
            ->placeholder('Enter your website URL');

// Address Information in Grid
$form->grid(3, 4)
    ->item()
        ->text()
            ->name('street')
            ->label('Street Address')
            ->placeholder('Enter street address')
            ->required()
    ->item()
        ->text()
            ->name('city')
            ->label('City')
            ->placeholder('Enter city')
            ->required()
    ->item()
        ->text()
            ->name('zip_code')
            ->label('ZIP Code')
            ->placeholder('Enter ZIP code')
            ->required();

// Additional Information in Cards
$form->card('Profile Information')
    ->textarea()
        ->name('bio')
        ->label('Biography')
        ->placeholder('Tell us about yourself')
    ->select()
        ->name('experience_level')
        ->label('Experience Level')
        ->options([
            'beginner' => 'Beginner',
            'intermediate' => 'Intermediate',
            'advanced' => 'Advanced',
            'expert' => 'Expert'
        ])
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

### Layout Components:

- `row()` - Creates a flex row container
- `column(int $width)` - Creates a column (width 1-12)
- `grid(int $cols, int $gap)` - Creates a CSS grid (1-6 columns)
- `section(string $title, string $description)` - Creates a section with title
- `card(string $title)` - Creates a card container

### Field Methods:

- `name(string $name)` - Set field name
- `value(string $value)` - Set field value
- `label(string $label)` - Set field label
- `placeholder(string $placeholder)` - Set placeholder text
- `required(bool $required = true)` - Make field required
- `options(array $options)` - Set options for select/radio fields
- `attribute(string $key, string $value)` - Add custom HTML attributes

### Features:

- **Advanced Layout Management**: Rows, columns, grids, sections, and cards
- **Responsive Design**: All layouts are mobile-friendly
- **Modern Styling**: Beautiful Tailwind CSS styling with gradients and animations
- **Accessibility**: Proper labels, focus states, and semantic HTML
- **Validation Ready**: Required fields are marked with asterisks
- **Customizable**: Easy to extend and customize styling
- **Type Safety**: Fluent interface with method chaining
- **Backend-Driven**: All layout logic controlled from PHP 