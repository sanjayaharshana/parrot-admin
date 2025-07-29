# Callable Options Documentation

## Overview

The callable options feature allows you to use functions or closures to dynamically generate options for select and radio fields. This enables database queries, API calls, conditional logic, and complex business rules to determine available options at runtime.

## Quick Start

```php
use Modules\UserPanel\Services\FormService;

$form = new FormService();

// Static options (traditional way)
$form->select()
    ->name('category')
    ->label('Category')
    ->options([
        'tech' => 'Technology',
        'design' => 'Design',
        'business' => 'Business'
    ])
    ->required();

// Callable options (new feature)
$form->select()
    ->name('uploader_id')
    ->label('Uploader')
    ->options(function () {
        // This could be a database query
        return [
            1 => 'John Doe (Admin)',
            2 => 'Jane Smith (Editor)',
            3 => 'Bob Johnson (Author)'
        ];
    })
    ->required();
```

## Basic Usage

### Simple Callable Function

```php
$form->select()
    ->name('country')
    ->label('Country')
    ->options(function () {
        return [
            'us' => 'United States',
            'uk' => 'United Kingdom',
            'ca' => 'Canada',
            'au' => 'Australia'
        ];
    })
    ->required();
```

### Database Integration

```php
$form->select()
    ->name('user_id')
    ->label('User')
    ->options(function () {
        // Query users from database
        return User::where('active', true)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    })
    ->required();
```

### Conditional Logic

```php
$form->radio()
    ->name('privilege')
    ->label('Privilege Level')
    ->options(function () {
        $privileges = [
            1 => 'Public (Everyone can view)',
            2 => 'Private (Only members)',
            3 => 'Restricted (Admin only)'
        ];
        
        // Add admin-only option if user is admin
        if (auth()->user() && auth()->user()->isAdmin()) {
            $privileges[4] = 'Super Admin (System only)';
        }
        
        return $privileges;
    })
    ->required();
```

## Advanced Examples

### User Role Selection with Permissions

```php
$form->select()
    ->name('user_role')
    ->label('User Role')
    ->options(function () {
        // Simulate database query with role hierarchy
        $roles = [
            'guest' => 'Guest (Read only)',
            'user' => 'User (Basic access)',
            'editor' => 'Editor (Content management)',
            'moderator' => 'Moderator (Community management)',
            'admin' => 'Administrator (Full access)'
        ];
        
        // Filter based on current user's permissions
        $currentUserRole = auth()->user() ? auth()->user()->role : 'guest';
        $allowedRoles = [];
        
        switch ($currentUserRole) {
            case 'admin':
                $allowedRoles = $roles;
                break;
            case 'moderator':
                $allowedRoles = array_slice($roles, 0, 4); // Exclude admin
                break;
            case 'editor':
                $allowedRoles = array_slice($roles, 0, 3); // Exclude admin, moderator
                break;
            default:
                $allowedRoles = array_slice($roles, 0, 2); // Only guest, user
        }
        
        return $allowedRoles;
    })
    ->required();
```

### Dynamic Timezone Selection

```php
$form->select()
    ->name('timezone')
    ->label('Timezone')
    ->options(function () {
        // Generate timezone options dynamically
        $timezones = [];
        $identifiers = timezone_identifiers_list();
        
        foreach ($identifiers as $identifier) {
            $timezone = new \DateTimeZone($identifier);
            $offset = $timezone->getOffset(new \DateTime()) / 3600;
            $offsetStr = sprintf('%+03d:00', $offset);
            $timezones[$identifier] = "($offsetStr) $identifier";
        }
        
        return $timezones;
    })
    ->required();
```

### Premium Features with Conditions

```php
$form->radio()
    ->name('theme')
    ->label('Theme')
    ->options(function () {
        $themes = [
            'light' => 'Light Theme',
            'dark' => 'Dark Theme',
            'auto' => 'Auto (System)'
        ];
        
        // Add premium themes if user has premium
        if (auth()->user() && auth()->user()->isPremium()) {
            $themes['custom'] = 'Custom Theme (Premium)';
            $themes['high_contrast'] = 'High Contrast (Premium)';
        }
        
        return $themes;
    })
    ->value('auto')
    ->required();
```

## Real-World Use Cases

### 1. User Management

```php
// Role selection based on user permissions
$form->select()
    ->name('role')
    ->label('Role')
    ->options(function () {
        return Role::where('level', '<=', auth()->user()->maxRoleLevel())
            ->pluck('display_name', 'id')
            ->toArray();
    })
    ->required();

// Department assignment from database
$form->select()
    ->name('department_id')
    ->label('Department')
    ->options(function () {
        return Department::active()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    })
    ->required();
```

### 2. Content Management

```php
// Category selection from active categories
$form->select()
    ->name('category_id')
    ->label('Category')
    ->options(function () {
        return Category::where('active', true)
            ->where('parent_id', null) // Only top-level categories
            ->pluck('name', 'id')
            ->toArray();
    })
    ->required();

// Author assignment from available users
$form->select()
    ->name('author_id')
    ->label('Author')
    ->options(function () {
        return User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['author', 'editor', 'admin']);
        })
        ->where('active', true)
        ->pluck('name', 'id')
        ->toArray();
    })
    ->required();
```

### 3. Settings & Preferences

```php
// Language options with user's locale
$form->select()
    ->name('language')
    ->label('Interface Language')
    ->options(function () {
        $languages = [
            'en' => 'English',
            'es' => 'Español',
            'fr' => 'Français',
            'de' => 'Deutsch'
        ];
        
        // Add user's browser language if not in list
        $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en', 0, 2);
        if (!isset($languages[$browserLang])) {
            $languages[$browserLang] = ucfirst($browserLang);
        }
        
        return $languages;
    })
    ->required();
```

### 4. Business Logic

```php
// Status options based on workflow
$form->select()
    ->name('status')
    ->label('Status')
    ->options(function () {
        $statuses = ['draft' => 'Draft'];
        
        if (auth()->user()->can('publish')) {
            $statuses['published'] = 'Published';
        }
        
        if (auth()->user()->can('approve')) {
            $statuses['pending'] = 'Pending Review';
            $statuses['approved'] = 'Approved';
        }
        
        if (auth()->user()->can('archive')) {
            $statuses['archived'] = 'Archived';
        }
        
        return $statuses;
    })
    ->required();
```

## Performance Considerations

### Caching Options

```php
$form->select()
    ->name('categories')
    ->label('Categories')
    ->options(function () {
        // Cache expensive queries
        return Cache::remember('active_categories', 3600, function () {
            return Category::active()
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();
        });
    })
    ->required();
```

### Lazy Loading

```php
$form->select()
    ->name('users')
    ->label('Users')
    ->options(function () {
        // Only load when form is rendered
        return User::select('id', 'name')
            ->where('active', true)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    })
    ->required();
```

## API Reference

### Field::options($options)

Sets the options for select and radio fields.

**Parameters:**
- `$options`: Array or callable function

**Returns:** Field instance for method chaining

**Examples:**
```php
// Array options
$field->options(['key' => 'value']);

// Callable options
$field->options(function () {
    return ['key' => 'value'];
});

// Closure with parameters
$field->options(function ($user) {
    return User::where('department_id', $user->department_id)
        ->pluck('name', 'id')
        ->toArray();
});
```

### Supported Field Types

Callable options work with:
- `select()` - Dropdown selection
- `radio()` - Radio button groups

### Callable Function Requirements

The callable function must:
1. Return an array with key-value pairs
2. Be callable (function, closure, or method)
3. Not require parameters (unless you pass them explicitly)

## Best Practices

### 1. Error Handling

```php
$form->select()
    ->name('users')
    ->label('Users')
    ->options(function () {
        try {
            return User::active()
                ->pluck('name', 'id')
                ->toArray();
        } catch (\Exception $e) {
            // Log error and return fallback options
            \Log::error('Failed to load users: ' . $e->getMessage());
            return ['error' => 'Unable to load users'];
        }
    })
    ->required();
```

### 2. Default Values

```php
$form->select()
    ->name('country')
    ->label('Country')
    ->options(function () {
        $countries = Country::active()->pluck('name', 'code')->toArray();
        
        // Add default option
        return ['' => 'Select a country'] + $countries;
    })
    ->required();
```

### 3. Conditional Logic

```php
$form->radio()
    ->name('notification_type')
    ->label('Notification Type')
    ->options(function () {
        $types = ['email' => 'Email'];
        
        // Add SMS option if user has phone
        if (auth()->user()->phone) {
            $types['sms'] = 'SMS';
        }
        
        // Add push option if user has mobile app
        if (auth()->user()->hasMobileApp()) {
            $types['push'] = 'Push Notification';
        }
        
        return $types;
    })
    ->required();
```

### 4. Performance Optimization

```php
$form->select()
    ->name('products')
    ->label('Products')
    ->options(function () {
        // Use eager loading to avoid N+1 queries
        return Product::with('category')
            ->where('active', true)
            ->get()
            ->mapWithKeys(function ($product) {
                return [$product->id => $product->name . ' (' . $product->category->name . ')'];
            })
            ->toArray();
    })
    ->required();
```

## Migration from Static Options

### Before (Static)
```php
$form->select()
    ->name('category')
    ->label('Category')
    ->options([
        'tech' => 'Technology',
        'design' => 'Design',
        'business' => 'Business'
    ])
    ->required();
```

### After (Callable)
```php
$form->select()
    ->name('category')
    ->label('Category')
    ->options(function () {
        return Category::active()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    })
    ->required();
```

## Examples

See the `CallableOptionsController` for complete working examples:
- Basic example: `/callable-options`
- Advanced example: `/callable-options/advanced`

## Benefits

1. **Dynamic Content**: Options can be generated based on current state, user permissions, or business logic
2. **Database Integration**: Easy integration with Eloquent models and relationships
3. **Performance**: Options are generated only when needed (lazy loading)
4. **Flexibility**: Support for complex conditional logic and API calls
5. **Maintainability**: Centralized logic for option generation
6. **Scalability**: Easy to add caching, filtering, and optimization 