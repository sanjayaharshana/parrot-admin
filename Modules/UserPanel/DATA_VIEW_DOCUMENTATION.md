# DataViewService Documentation

## Overview

The `DataViewService` provides a powerful grid-based data display system that follows the pattern you described. It allows you to create data tables with sortable columns, custom display logic, search functionality, and action buttons.

## Quick Start

```php
use Modules\UserPanel\Services\DataViewService;
use App\Models\User;

// Create a new DataViewService instance
$grid = new DataViewService(new User);

// The first column displays the id field and sets the column as a sortable column
$grid->id('ID')->sortable();

// The second column shows the name field
$grid->column('name', 'Full Name')->sortable();

// The third column shows the email field
$grid->column('email', 'Email Address')->sortable();

// The fourth column shows the created_at field with custom formatting
$grid->column('created_at', 'Joined Date')->display(function($value) {
    return $value ? date('M d, Y', strtotime($value)) : 'N/A';
});

// The fifth column shows the email_verified_at field with status indicator
$grid->column('email_verified_at', 'Status')->display(function($value) {
    if ($value) {
        return '<span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Verified</span>';
    }
    return '<span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Unverified</span>';
});

// Configure grid settings
$grid->perPage(10)
    ->defaultSort('created_at', 'desc')
    ->search(true)
    ->pagination(true);

// Render the grid
echo $grid->render();
```

## Basic Usage

### Creating a Grid

```php
// Create with model instance
$grid = new DataViewService(new User);

// Or create with model class
$grid = new DataViewService(User::class);
```

### Defining Columns

#### ID Column
```php
// Default ID column (sortable by default)
$grid->id('ID')->sortable();

// Custom ID column
$grid->id('User ID')->sortable();
```

#### Regular Columns
```php
// Basic column
$grid->column('name', 'Full Name');

// Sortable column
$grid->column('email', 'Email Address')->sortable();

// Searchable column
$grid->column('phone', 'Phone Number')->searchable();
```

#### Columns with Custom Display
```php
// Custom display logic
$grid->column('created_at', 'Joined Date')->display(function($value) {
    return $value ? date('M d, Y', strtotime($value)) : 'N/A';
});

// Display with model access
$grid->column('user_id', 'User')->display(function($value, $model) {
    $user = User::find($value);
    return $user ? $user->name : 'Unknown';
});

// HTML content
$grid->column('status', 'Status')->display(function($value) {
    $color = $value === 'active' ? 'green' : 'red';
    return "<span class='text-{$color}-600 font-semibold'>" . ucfirst($value) . "</span>";
});
```

#### Actions Column
```php
$grid->actions([
    [
        'label' => 'View',
        'url' => function($model) {
            return route('users.show', $model->id);
        },
        'class' => 'px-3 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600',
        'icon' => 'fas fa-eye'
    ],
    [
        'label' => 'Edit',
        'url' => function($model) {
            return route('users.edit', $model->id);
        },
        'class' => 'px-3 py-1 text-xs bg-green-500 text-white rounded hover:bg-green-600',
        'icon' => 'fas fa-edit'
    ],
    [
        'label' => 'Delete',
        'url' => function($model) {
            return route('users.destroy', $model->id);
        },
        'class' => 'px-3 py-1 text-xs bg-red-500 text-white rounded hover:bg-red-600',
        'icon' => 'fas fa-trash'
    ]
]);
```

### Grid Configuration

#### Pagination
```php
// Set items per page
$grid->perPage(15);

// Disable pagination
$grid->pagination(false);
```

#### Sorting
```php
// Set default sort column and order
$grid->defaultSort('created_at', 'desc');

// Make column sortable
$grid->column('name')->sortable();
```

#### Search
```php
// Enable search
$grid->search(true);

// Disable search
$grid->search(false);
```

#### Filters
```php
// Enable filters
$grid->filters(true);

// Disable filters
$grid->filters(false);
```

#### Bulk Actions
```php
$grid->bulkActions([
    [
        'label' => 'Activate Selected',
        'action' => 'activate',
        'class' => 'bg-green-500 hover:bg-green-600'
    ],
    [
        'label' => 'Deactivate Selected',
        'action' => 'deactivate',
        'class' => 'bg-red-500 hover:bg-red-600'
    ],
    [
        'label' => 'Delete Selected',
        'action' => 'delete',
        'class' => 'bg-red-600 hover:bg-red-700'
    ]
]);
```

## Advanced Examples

### Complex Grid with Relationships

```php
$grid = new DataViewService(new Post);

// ID column
$grid->id('ID')->sortable();

// Title with excerpt
$grid->column('title', 'Post Title')->display(function($value, $post) {
    $excerpt = Str::limit($post->content, 100);
    return "
        <div>
            <div class='font-semibold'>{$value}</div>
            <div class='text-sm text-gray-500'>{$excerpt}</div>
        </div>
    ";
});

// Author with avatar
$grid->column('author_id', 'Author')->display(function($value, $post) {
    $author = $post->author;
    $avatar = $author->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($author->name);
    return "
        <div class='flex items-center'>
            <img class='h-8 w-8 rounded-full mr-3' src='{$avatar}' alt='{$author->name}'>
            <span>{$author->name}</span>
        </div>
    ";
});

// Category with color coding
$grid->column('category_id', 'Category')->display(function($value, $post) {
    $category = $post->category;
    $colors = [
        'technology' => 'bg-blue-100 text-blue-800',
        'lifestyle' => 'bg-green-100 text-green-800',
        'business' => 'bg-purple-100 text-purple-800'
    ];
    $color = $colors[$category->slug] ?? 'bg-gray-100 text-gray-800';
    return "<span class='px-2 py-1 text-xs font-semibold rounded-full {$color}'>{$category->name}</span>";
});

// Status with toggle
$grid->column('status', 'Status')->display(function($value, $post) {
    $status = $value === 'published' ? 'Published' : 'Draft';
    $color = $value === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
    return "<span class='px-2 py-1 text-xs font-semibold rounded-full {$color}'>{$status}</span>";
});

// Created date with relative time
$grid->column('created_at', 'Created')->display(function($value) {
    return date('M d, Y') . '<br><span class="text-xs text-gray-500">' . 
           Carbon::parse($value)->diffForHumans() . '</span>';
});

// Actions with dropdown
$grid->actions([
    [
        'label' => 'View',
        'url' => function($post) {
            return route('posts.show', $post->id);
        },
        'class' => 'px-3 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600'
    ],
    [
        'label' => 'Edit',
        'url' => function($post) {
            return route('posts.edit', $post->id);
        },
        'class' => 'px-3 py-1 text-xs bg-green-500 text-white rounded hover:bg-green-600'
    ],
    [
        'label' => 'Delete',
        'url' => function($post) {
            return route('posts.destroy', $post->id);
        },
        'class' => 'px-3 py-1 text-xs bg-red-500 text-white rounded hover:bg-red-600'
    ]
]);

// Configure grid
$grid->perPage(20)
    ->defaultSort('created_at', 'desc')
    ->search(true)
    ->filters(true)
    ->pagination(true);
```

### Grid with Custom Filters

```php
$grid = new DataViewService(new User);

// Add custom filters
$grid->addFilter('role', function($query, $value) {
    $query->where('role', $value);
});

$grid->addFilter('status', function($query, $value) {
    if ($value === 'active') {
        $query->where('is_active', true);
    } elseif ($value === 'inactive') {
        $query->where('is_active', false);
    }
});

$grid->addFilter('date_range', function($query, $value) {
    $dates = explode(' to ', $value);
    if (count($dates) === 2) {
        $query->whereBetween('created_at', $dates);
    }
});
```

### Grid with Export Functionality

```php
$grid = new DataViewService(new Order);

// Add export actions
$grid->actions([
    [
        'label' => 'Export CSV',
        'url' => function($order) {
            return route('orders.export.csv', $order->id);
        },
        'class' => 'px-3 py-1 text-xs bg-purple-500 text-white rounded hover:bg-purple-600'
    ],
    [
        'label' => 'Export PDF',
        'url' => function($order) {
            return route('orders.export.pdf', $order->id);
        },
        'class' => 'px-3 py-1 text-xs bg-orange-500 text-white rounded hover:bg-orange-600'
    ]
]);
```

## Column Methods

### Column Configuration

```php
$column = $grid->column('name', 'Full Name');

// Make sortable
$column->sortable(true);

// Make searchable
$column->searchable(true);

// Add custom display logic
$column->display(function($value, $model) {
    return ucfirst($value);
});

// Add custom attributes
$column->attribute('data-column-type', 'text');
$column->attribute('class', 'custom-column');
```

### Column Types

#### Text Column
```php
$grid->column('name', 'Name');
```

#### Number Column
```php
$grid->column('price', 'Price')->display(function($value) {
    return '$' . number_format($value, 2);
});
```

#### Date Column
```php
$grid->column('created_at', 'Created')->display(function($value) {
    return date('M d, Y', strtotime($value));
});
```

#### Boolean Column
```php
$grid->column('is_active', 'Active')->display(function($value) {
    return $value ? 'Yes' : 'No';
});
```

#### Image Column
```php
$grid->column('avatar', 'Avatar')->display(function($value) {
    return "<img src='{$value}' class='h-10 w-10 rounded-full' alt='Avatar'>";
});
```

#### Link Column
```php
$grid->column('website', 'Website')->display(function($value) {
    return "<a href='{$value}' target='_blank' class='text-blue-600 hover:underline'>{$value}</a>";
});
```

## Grid Rendering

### Basic Rendering
```php
// Render to HTML
echo $grid->render();

// Get data array
$data = $grid->getData();
```

### Custom Rendering
```php
// Get data and render manually
$data = $grid->getData();

// Access formatted data
foreach ($data['data'] as $row) {
    echo $row['name'] . ' - ' . $row['email'] . '<br>';
}

// Access pagination
if ($data['pagination']) {
    echo $data['pagination']->links();
}
```

## Controller Integration

### Basic Controller
```php
public function index()
{
    $grid = new DataViewService(new User);
    
    $grid->id('ID')->sortable();
    $grid->column('name', 'Name')->sortable();
    $grid->column('email', 'Email')->sortable();
    $grid->column('created_at', 'Joined')->display(function($value) {
        return date('M d, Y', strtotime($value));
    });
    
    $grid->actions([
        [
            'label' => 'Edit',
            'url' => function($user) {
                return route('users.edit', $user->id);
            }
        ]
    ]);
    
    return view('users.index', [
        'grid' => $grid,
        'title' => 'Users'
    ]);
}
```

### Advanced Controller with Filters
```php
public function index(Request $request)
{
    $grid = new DataViewService(new User);
    
    // Add filters based on request
    if ($request->has('role')) {
        $grid->addFilter('role', function($query, $value) {
            $query->where('role', $value);
        });
    }
    
    if ($request->has('status')) {
        $grid->addFilter('status', function($query, $value) {
            $query->where('is_active', $value === 'active');
        });
    }
    
    // Build grid
    $grid->id('ID')->sortable();
    $grid->column('name', 'Name')->sortable();
    $grid->column('email', 'Email')->sortable();
    $grid->column('role', 'Role')->display(function($value) {
        return ucfirst($value);
    });
    $grid->column('is_active', 'Status')->display(function($value) {
        return $value ? 'Active' : 'Inactive';
    });
    
    return view('users.index', [
        'grid' => $grid,
        'title' => 'Users'
    ]);
}
```

## View Integration

### Basic View
```blade
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">{{ $title }}</h1>
    
    {!! $grid->render() !!}
</div>
@endsection
```

### Advanced View with Custom Styling
```blade
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">{{ $title }}</h1>
        <a href="{{ route('users.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Add New User
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow">
        {!! $grid->render() !!}
    </div>
</div>
@endsection
```

## API Reference

### DataViewService Methods

#### Constructor
```php
new DataViewService(Model $model)
```

#### Column Methods
- `id(string $label = 'ID'): Column`
- `column(string $field, string $label = null): Column`
- `display(string $field, string $label = null, callable $callback = null): Column`
- `actions(array $actions = []): Column`

#### Configuration Methods
- `perPage(int $perPage): self`
- `defaultSort(string $column, string $order = 'desc'): self`
- `pagination(bool $show = true): self`
- `search(bool $show = true): self`
- `filters(bool $show = true): self`
- `bulkActions(array $actions): self`
- `attribute(string $key, string $value): self`

#### Data Methods
- `getData(): array`
- `render(): string`

### Column Methods

#### Configuration
- `sortable(bool $sortable = true): self`
- `searchable(bool $searchable = true): self`
- `display(callable $callback): self`
- `actions(array $actions): self`
- `attribute(string $key, string $value): self`

#### Getters
- `getField(): string`
- `getLabel(): string`
- `isSortable(): bool`
- `isSearchable(): bool`
- `hasDisplayCallback(): bool`
- `getDisplayCallback(): callable`
- `getActions(): array`
- `getAttributes(): array`

## Best Practices

### 1. Performance Optimization
```php
// Use eager loading for relationships
$grid = new DataViewService(User::with(['profile', 'posts']));

// Limit columns to what's needed
$grid->column('name', 'Name');
$grid->column('email', 'Email');
// Avoid loading unnecessary fields
```

### 2. Security
```php
// Sanitize HTML output in display callbacks
$grid->column('content', 'Content')->display(function($value) {
    return e(Str::limit($value, 100));
});

// Validate user permissions for actions
$grid->actions([
    [
        'label' => 'Edit',
        'url' => function($user) {
            if (auth()->user()->can('edit', $user)) {
                return route('users.edit', $user->id);
            }
            return '#';
        }
    ]
]);
```

### 3. Accessibility
```php
// Add proper ARIA labels
$grid->column('name', 'Name')->attribute('aria-label', 'User name');

// Ensure proper table structure
$grid->attribute('role', 'table');
$grid->attribute('aria-label', 'Users data table');
```

### 4. Responsive Design
```php
// Use responsive classes
$grid->column('name', 'Name')->attribute('class', 'hidden md:table-cell');

// Add responsive wrapper
echo '<div class="overflow-x-auto">' . $grid->render() . '</div>';
```

## Examples

See the `DataViewController` for complete working examples:
- Basic grid: `/data-view`
- Advanced grid: `/data-view/advanced`

## Benefits

1. **Rapid Development**: Quick data grid creation with minimal code
2. **Automatic Features**: Built-in sorting, searching, and pagination
3. **Customizable**: Flexible column definitions and display logic
4. **Responsive**: Modern, responsive design with Tailwind CSS
5. **Accessible**: Proper HTML structure and ARIA labels
6. **Performance**: Efficient queries and optimized rendering
7. **Extensible**: Easy to extend with custom features
8. **Consistent**: Uniform look and behavior across grids 