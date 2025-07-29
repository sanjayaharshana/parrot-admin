# LayoutService Documentation

## Overview

The LayoutService is a separate, independent layout management system that allows you to create and manage layouts independently from forms. This provides greater flexibility, reusability, and separation of concerns in your application.

## Table of Contents

1. [Quick Start](#quick-start)
2. [Basic Usage](#basic-usage)
3. [Layout Components](#layout-components)
4. [Field Binding](#field-binding)
5. [Advanced Features](#advanced-features)
6. [Best Practices](#best-practices)
7. [Examples](#examples)
8. [API Reference](#api-reference)

## Quick Start

### Installation

The LayoutService is included in the UserPanel module. No additional installation required.

### Basic Example

```php
use Modules\UserPanel\Services\LayoutService;
use Modules\UserPanel\Services\FormService;

// Create layout
$layout = new LayoutService();

// Build layout structure
$section = $layout->section('Personal Information');
$section->addContent('<p>This is a custom section</p>');

// Create form fields
$form = new FormService();
$field = $form->text()->name('name')->label('Name');

// Bind field to layout
$section->addField($field);

// Render layout
$html = $layout->render();
```

## Basic Usage

### Creating a Layout

```php
use Modules\UserPanel\Services\LayoutService;

$layout = new LayoutService();
```

### Adding Layout Components

```php
// Add a section
$section = $layout->section('Title', 'Description');

// Add a row with columns
$row = $layout->row();
$column1 = $row->column(6);
$column2 = $row->column(6);

// Add a grid
$grid = $layout->grid(3, 4);
$item1 = $grid->item();
$item2 = $grid->item();
$item3 = $grid->item();

// Add a card
$card = $layout->card('Card Title');
```

### Rendering the Layout

```php
$html = $layout->render();
```

## Layout Components

### Row Container

Creates a flexbox row container:

```php
$row = $layout->row('flex flex-wrap -mx-3');

// Add columns to row
$column1 = $row->column(6);
$column2 = $row->column(6);
```

### Column Container

Creates responsive columns:

```php
// Full width column
$column = $layout->column(12);

// Half width column
$column = $layout->column(6);

// Third width column
$column = $layout->column(4);

// Custom classes
$column = $layout->column(6, 'bg-gray-100 p-4');
```

### Grid Container

Creates CSS Grid layouts:

```php
// 2-column grid
$grid = $layout->grid(2, 6);

// 3-column grid
$grid = $layout->grid(3, 4);

// 4-column grid
$grid = $layout->grid(4, 3);

// Add items to grid
$item1 = $grid->item();
$item2 = $grid->item();
$item3 = $grid->item();
```

### Section Container

Creates sections with titles and descriptions:

```php
// Section with title only
$section = $layout->section('Personal Information');

// Section with title and description
$section = $layout->section(
    'Personal Information',
    'Please provide your basic details.'
);
```

### Card Container

Creates contained content areas:

```php
// Card with title
$card = $layout->card('Contact Information');

// Card without title
$card = $layout->card();
```

### Divider

Creates visual separators:

```php
// Default divider
$layout->divider();

// Custom divider
$layout->divider('border-t-2 border-blue-300 my-8');
```

### Spacer

Creates vertical spacing:

```php
// Default spacer (h-6)
$layout->spacer();

// Custom height spacer
$layout->spacer(12); // h-12
```

### Container

Creates custom containers:

```php
// Default container
$container = $layout->container();

// Custom container
$container = $layout->container('bg-blue-50 p-8 rounded-xl');
```

## Field Binding

### Binding Form Fields

You can bind FormService fields to layout components:

```php
use Modules\UserPanel\Services\FormService;

$form = new FormService();
$layout = new LayoutService();

// Create form field
$field = $form->text()
    ->name('username')
    ->label('Username')
    ->required();

// Bind to layout
$section = $layout->section('User Information');
$section->addField($field);
```

### Adding Custom Content

You can also add custom HTML content:

```php
$section = $layout->section('Information');

// Add custom HTML
$section->addContent('<p class="text-sm text-gray-600">Custom content here</p>');

// Add form field
$section->addField($field);
```

## Advanced Features

### Complex Layout Building

```php
$layout = new LayoutService();

// Build complex layout structure
$section = $layout->section('User Profile', 'Complete your profile information');

$row = $layout->row();
$row->column(6)->addContent('<h4>Personal Info</h4>');
$row->column(6)->addContent('<h4>Contact Info</h4>');

$grid = $layout->grid(3, 4);
$grid->item()->addContent('<p>Grid Item 1</p>');
$grid->item()->addContent('<p>Grid Item 2</p>');
$grid->item()->addContent('<p>Grid Item 3</p>');

$card = $layout->card('Additional Information');
$card->addContent('<p>Card content here</p>');

$layout->divider();

$layout->spacer(8);
```

### Layout Reusability

```php
class LayoutBuilder
{
    public function createUserProfileLayout(): LayoutService
    {
        $layout = new LayoutService();
        
        $layout->section('Personal Information');
        $layout->row()->column(6)->column(6);
        $layout->card('Contact Details');
        
        return $layout;
    }
    
    public function createSettingsLayout(): LayoutService
    {
        $layout = new LayoutService();
        
        $layout->section('Account Settings');
        $layout->grid(2, 6);
        $layout->card('Preferences');
        
        return $layout;
    }
}

// Usage
$builder = new LayoutBuilder();
$userLayout = $builder->createUserProfileLayout();
$settingsLayout = $builder->createSettingsLayout();
```

### Dynamic Layout Generation

```php
function createDynamicLayout(array $sections): LayoutService
{
    $layout = new LayoutService();
    
    foreach ($sections as $section) {
        $layoutSection = $layout->section($section['title'], $section['description']);
        
        if (isset($section['fields'])) {
            foreach ($section['fields'] as $field) {
                $layoutSection->addField($field);
            }
        }
    }
    
    return $layout;
}

// Usage
$sections = [
    [
        'title' => 'Personal Info',
        'description' => 'Basic information',
        'fields' => [$nameField, $emailField]
    ],
    [
        'title' => 'Address',
        'description' => 'Contact address',
        'fields' => [$addressField, $cityField]
    ]
];

$layout = createDynamicLayout($sections);
```

## Best Practices

### 1. Separation of Concerns

```php
// Good: Separate layout and form logic
class UserController
{
    public function create()
    {
        // Build layout
        $layout = $this->buildUserLayout();
        
        // Create form fields
        $form = new FormService();
        $fields = $this->createUserFields($form);
        
        // Bind fields to layout
        $this->bindFieldsToLayout($layout, $fields);
        
        return view('users.create', ['layout' => $layout->render()]);
    }
    
    private function buildUserLayout(): LayoutService
    {
        $layout = new LayoutService();
        $layout->section('Personal Information');
        $layout->row()->column(6)->column(6);
        return $layout;
    }
    
    private function createUserFields(FormService $form): array
    {
        return [
            'name' => $form->text()->name('name')->label('Name'),
            'email' => $form->email()->name('email')->label('Email')
        ];
    }
    
    private function bindFieldsToLayout(LayoutService $layout, array $fields): void
    {
        // Bind fields to layout components
    }
}
```

### 2. Layout Reusability

```php
// Create reusable layout templates
class LayoutTemplates
{
    public static function twoColumnForm(): LayoutService
    {
        $layout = new LayoutService();
        $row = $layout->row();
        $row->column(6);
        $row->column(6);
        return $layout;
    }
    
    public static function threeColumnGrid(): LayoutService
    {
        $layout = new LayoutService();
        $grid = $layout->grid(3, 4);
        $grid->item();
        $grid->item();
        $grid->item();
        return $layout;
    }
}

// Usage
$layout = LayoutTemplates::twoColumnForm();
```

### 3. Conditional Layouts

```php
function createConditionalLayout(User $user): LayoutService
{
    $layout = new LayoutService();
    
    // Always show basic info
    $layout->section('Basic Information');
    
    // Show additional sections based on user type
    if ($user->isBusiness()) {
        $layout->section('Business Information');
        $layout->card('Company Details');
    }
    
    if ($user->hasAddress()) {
        $layout->section('Address Information');
        $layout->grid(3, 4);
    }
    
    return $layout;
}
```

## Examples

### Complete User Registration Layout

```php
use Modules\UserPanel\Services\LayoutService;
use Modules\UserPanel\Services\FormService;

class UserRegistrationLayout
{
    public function create(): LayoutService
    {
        $layout = new LayoutService();
        
        // Personal Information Section
        $section = $layout->section('Personal Information', 'Please provide your basic details.');
        
        // Contact Information Row
        $row = $layout->row();
        $row->column(6); // Phone
        $row->column(6); // Website
        
        // Address Grid
        $grid = $layout->grid(3, 4);
        $grid->item(); // Street
        $grid->item(); // City
        $grid->item(); // ZIP
        
        // Profile Card
        $layout->card('Profile Information');
        
        // Preferences Card
        $layout->card('Preferences');
        
        return $layout;
    }
    
    public function bindFields(LayoutService $layout, FormService $form): void
    {
        $fields = $this->createFields($form);
        $this->bindFieldsToLayout($layout, $fields);
    }
    
    private function createFields(FormService $form): array
    {
        return [
            'first_name' => $form->text()->name('first_name')->label('First Name')->required(),
            'last_name' => $form->text()->name('last_name')->label('Last Name')->required(),
            'email' => $form->email()->name('email')->label('Email')->required(),
            'phone' => $form->text()->name('phone')->label('Phone')->required(),
            'website' => $form->text()->name('website')->label('Website'),
            'street' => $form->text()->name('street')->label('Street')->required(),
            'city' => $form->text()->name('city')->label('City')->required(),
            'zip' => $form->text()->name('zip')->label('ZIP')->required(),
            'bio' => $form->textarea()->name('bio')->label('Biography'),
            'country' => $form->select()->name('country')->label('Country')->options([
                'us' => 'United States',
                'uk' => 'United Kingdom'
            ])->required(),
            'newsletter' => $form->checkbox()->name('newsletter')->label('Subscribe to newsletter'),
            'gender' => $form->radio()->name('gender')->label('Gender')->options([
                'male' => 'Male',
                'female' => 'Female'
            ])->required()
        ];
    }
    
    private function bindFieldsToLayout(LayoutService $layout, array $fields): void
    {
        $layoutItems = $layout->getLayout();
        
        foreach ($layoutItems as $item) {
            if ($item instanceof Section) {
                $item->addField($fields['first_name']);
                $item->addField($fields['last_name']);
                $item->addField($fields['email']);
            }
            elseif ($item instanceof Row) {
                $columns = $item->getColumns();
                if (count($columns) >= 2) {
                    $columns[0]->addField($fields['phone']);
                    $columns[1]->addField($fields['website']);
                }
            }
            elseif ($item instanceof Grid) {
                $gridItems = $item->getItems();
                if (count($gridItems) >= 3) {
                    $gridItems[0]->addField($fields['street']);
                    $gridItems[1]->addField($fields['city']);
                    $gridItems[2]->addField($fields['zip']);
                }
            }
            elseif ($item instanceof Card) {
                $content = $item->getContent();
                if (empty($content)) {
                    if (strpos($item->render(), 'Profile Information') !== false) {
                        $item->addField($fields['bio']);
                    } else {
                        $item->addField($fields['country']);
                        $item->addField($fields['newsletter']);
                        $item->addField($fields['gender']);
                    }
                }
            }
        }
    }
}

// Usage
$layoutBuilder = new UserRegistrationLayout();
$layout = $layoutBuilder->create();

$form = new FormService();
$layoutBuilder->bindFields($layout, $form);

$html = $layout->render();
```

### Simple Contact Form Layout

```php
$layout = new LayoutService();

$layout->section('Contact Information', 'Please provide your contact details.');

$row = $layout->row();
$row->column(6)->addContent('<p>Left column</p>');
$row->column(6)->addContent('<p>Right column</p>');

$layout->card('Additional Information');

$html = $layout->render();
```

## API Reference

### LayoutService Methods

| Method | Description | Parameters | Returns |
|--------|-------------|------------|---------|
| `row(string $classes)` | Create row container | `$classes` (optional) | Row |
| `column(int $width, string $classes)` | Create column container | `$width` (1-12), `$classes` (optional) | Column |
| `grid(int $cols, int $gap)` | Create grid container | `$cols` (1-6), `$gap` (1-12) | Grid |
| `section(string $title, string $description)` | Create section | `$title`, `$description` (optional) | Section |
| `card(string $title)` | Create card container | `$title` (optional) | Card |
| `divider(string $classes)` | Create divider | `$classes` (optional) | Divider |
| `spacer(int $height)` | Create spacer | `$height` (1-12) | Spacer |
| `container(string $classes)` | Create custom container | `$classes` (optional) | Container |
| `render()` | Render complete layout | None | string |
| `getLayout()` | Get layout items | None | array |
| `clear()` | Clear all layout items | None | self |
| `addItem(LayoutItem $item)` | Add custom layout item | `$item` | self |

### Layout Component Methods

All layout components implement the `LayoutItem` interface and have these common methods:

| Method | Description | Parameters | Returns |
|--------|-------------|------------|---------|
| `addContent(string $content)` | Add HTML content | `$content` | self |
| `addField(Field $field)` | Add form field | `$field` | self |
| `render()` | Render component HTML | None | string |
| `getContent()` | Get component content | None | array |

### Row Methods

| Method | Description | Parameters | Returns |
|--------|-------------|------------|---------|
| `column(int $width, string $classes)` | Create column in row | `$width`, `$classes` | Column |
| `getColumns()` | Get row columns | None | array |

### Grid Methods

| Method | Description | Parameters | Returns |
|--------|-------------|------------|---------|
| `item()` | Create grid item | None | GridItem |
| `getItems()` | Get grid items | None | array |

## Styling

The LayoutService uses Tailwind CSS for styling and includes:

- **Responsive Design**: All layouts adapt to different screen sizes
- **Flexible Grid System**: 12-column grid with responsive breakpoints
- **Modern Components**: Cards, sections, dividers with professional styling
- **Customizable**: All components accept custom CSS classes
- **Consistent Spacing**: Standardized spacing and padding

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## Troubleshooting

### Common Issues

1. **Layout not rendering**
   - Ensure `render()` method is called
   - Check that layout components are properly added

2. **Fields not appearing**
   - Verify `addField()` is called on layout components
   - Check that FormService fields are properly created

3. **Styling issues**
   - Ensure Tailwind CSS is loaded
   - Check for CSS conflicts

### Debug Tips

```php
// Debug layout structure
$layout = new LayoutService();
// ... add components ...
dd($layout->getLayout()); // Inspect layout items

// Debug rendered HTML
$html = $layout->render();
dd($html); // Inspect generated HTML
```

## Contributing

When extending the LayoutService:

1. Follow the existing naming conventions
2. Implement the `LayoutItem` interface for new components
3. Add proper documentation for new methods
4. Include examples in this documentation
5. Test with different layouts and content types
6. Ensure responsive design compatibility

---

For more information, see the main UserPanel module documentation or contact the development team. 