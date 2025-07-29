# Model Binding Documentation

## Overview

The model binding feature allows you to automatically bind Eloquent models to forms, enabling automatic data population, validation, and form submission handling. This provides a seamless way to create, edit, and manage database records through forms.

## Quick Start

```php
use Modules\UserPanel\Services\FormService;
use Modules\UserPanel\Services\LayoutService;
use App\Models\User;

$form = new FormService();
$layout = new LayoutService();
$layout->setFormService($form);

// For creating new records
$form->create(User::class)
    ->method('POST')
    ->action(route('users.store'));

// For editing existing records
$form->find(User::class, $id)
    ->method('PUT')
    ->action(route('users.update', $id));

// Build form (fields auto-populate from model)
$layout->section('User Info', function ($form, $section) {
    $section->addField(
        $form->text()
            ->name('name')  // Auto-populates from model
            ->label('Name')
            ->required()
    );
});

// Handle form submission
public function store(Request $request)
{
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users'
    ];
    
    $result = $form->handle($request, $rules);
    
    if ($result['success']) {
        return redirect()->with('success', 'User created!');
    }
    
    return back()->withErrors($result['errors']);
}
```

## Basic Usage

### Creating New Records

```php
// Create a new model instance
$form->create(User::class, [
    'role' => 'user',
    'is_active' => true
])
->method('POST')
->action(route('users.store'));

// Build form with default values
$layout->section('User Information', function ($form, $section) {
    $section->addField(
        $form->text()
            ->name('name')
            ->label('Full Name')
            ->required()
    );
    
    $section->addField(
        $form->email()
            ->name('email')
            ->label('Email Address')
            ->required()
    );
});
```

### Editing Existing Records

```php
// Find and bind existing model
$form->find(User::class, $id)
    ->method('PUT')
    ->action(route('users.update', $id));

// Fields automatically populate with model data
$layout->section('User Information', function ($form, $section) {
    $section->addField(
        $form->text()
            ->name('name')  // Will show current user name
            ->label('Full Name')
            ->required()
    );
    
    $section->addField(
        $form->email()
            ->name('email')  // Will show current user email
            ->label('Email Address')
            ->required()
    );
});
```

### Manual Model Binding

```php
// Bind an existing model instance
$user = User::find(1);
$form->bindModel($user)
    ->method('PUT')
    ->action(route('users.update', $user->id));
```

## Form Configuration

### HTTP Methods

```php
// Set form method
$form->method('POST');    // Create
$form->method('PUT');     // Update
$form->method('PATCH');   // Partial update
$form->method('DELETE');  // Delete
```

### Form Action

```php
// Set form action URL
$form->action(route('users.store'));
$form->action('/api/users');
$form->action('https://example.com/submit');
```

### Form Attributes

```php
// Add custom form attributes
$form->formAttribute('enctype', 'multipart/form-data')
    ->formAttribute('target', '_blank')
    ->formAttribute('data-form-type', 'user-registration');
```

## Form Submission Handling

### Basic Validation

```php
public function store(Request $request)
{
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:user,editor,admin'
    ];
    
    $messages = [
        'name.required' => 'Please enter the user\'s full name.',
        'email.unique' => 'This email address is already registered.',
        'password.confirmed' => 'Password confirmation does not match.'
    ];
    
    $result = $form->handle($request, $rules, $messages);
    
    if ($result['success']) {
        return redirect()->with('success', $result['message']);
    }
    
    return back()->withErrors($result['errors']);
}
```

### Advanced Validation

```php
public function update(Request $request, $id)
{
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'password' => 'nullable|string|min:8|confirmed',
        'phone' => 'nullable|string|max:20',
        'role' => 'required|in:user,editor,admin',
        'is_active' => 'boolean'
    ];
    
    $result = $form->find(User::class, $id)->handle($request, $rules);
    
    if ($result['success']) {
        // Custom logic after successful update
        $user = $result['model'];
        
        // Send notification
        $user->notify(new ProfileUpdatedNotification());
        
        return redirect()->with('success', 'Profile updated successfully!');
    }
    
    return back()->withErrors($result['errors']);
}
```

## Model Value Access

### Direct Attributes

```php
// Access model attributes directly
$form->text()->name('name');        // $model->name
$form->email()->name('email');      // $model->email
$form->text()->name('phone');       // $model->phone
```

### Nested Attributes

```php
// Access nested relationships
$form->text()->name('profile.bio');           // $model->profile->bio
$form->text()->name('company.name');          // $model->company->name
$form->select()->name('department.id');       // $model->department->id
```

### Computed Properties

```php
// Access computed properties and methods
$form->text()->name('full_name');             // $model->full_name
$form->text()->name('age');                   // $model->age()
$form->text()->name('formatted_phone');       // $model->formatted_phone()
```

## Advanced Examples

### Complex Form with Relationships

```php
// Create a post with author relationship
$form->create(Post::class)
    ->method('POST')
    ->action(route('posts.store'));

$layout->section('Post Information', function ($form, $section) {
    $section->addField(
        $form->text()
            ->name('title')
            ->label('Post Title')
            ->required()
    );
    
    $section->addField(
        $form->textarea()
            ->name('content')
            ->label('Post Content')
            ->required()
    );
    
    $section->addField(
        $form->select()
            ->name('author_id')
            ->label('Author')
            ->options(function () {
                return User::where('role', 'author')
                    ->pluck('name', 'id')
                    ->toArray();
            })
            ->required()
    );
    
    $section->addField(
        $form->select()
            ->name('category_id')
            ->label('Category')
            ->options(function () {
                return Category::active()
                    ->pluck('name', 'id')
                    ->toArray();
            })
            ->required()
    );
});

$layout->section('Publishing', function ($form, $section) {
    $section->addField(
        $form->select()
            ->name('status')
            ->label('Status')
            ->options([
                'draft' => 'Draft',
                'published' => 'Published',
                'archived' => 'Archived'
            ])
            ->value('draft')
    );
    
    $section->addField(
        $form->checkbox()
            ->name('is_featured')
            ->label('Featured Post')
            ->value('1')
    );
});
```

### Form with File Uploads

```php
$form->create(Product::class)
    ->method('POST')
    ->action(route('products.store'))
    ->formAttribute('enctype', 'multipart/form-data');

$layout->section('Product Details', function ($form, $section) {
    $section->addField(
        $form->text()
            ->name('name')
            ->label('Product Name')
            ->required()
    );
    
    $section->addField(
        $form->textarea()
            ->name('description')
            ->label('Description')
            ->required()
    );
    
    $section->addField(
        $form->number()
            ->name('price')
            ->label('Price')
            ->required()
    );
    
    // Add custom file upload component
    $section->addComponent('file-upload', [
        'name' => 'images[]',
        'label' => 'Product Images',
        'multiple' => true,
        'accept' => 'image/*',
        'maxSize' => '5MB'
    ]);
});

public function store(Request $request)
{
    $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'images.*' => 'image|mimes:jpeg,png,jpg|max:5120'
    ];
    
    $result = $form->handle($request, $rules);
    
    if ($result['success']) {
        $product = $result['model'];
        
        // Handle file uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['path' => $path]);
            }
        }
        
        return redirect()->with('success', 'Product created successfully!');
    }
    
    return back()->withErrors($result['errors']);
}
```

## API Reference

### FormService Methods

#### `bindModel(Model $model)`
Binds an existing model instance to the form.

#### `create(string $modelClass, array $attributes = [])`
Creates a new model instance and binds it to the form.

**Parameters:**
- `$modelClass`: The model class name
- `$attributes`: Default attributes for the new model

#### `find(string $modelClass, $id)`
Finds an existing model by ID and binds it to the form.

**Parameters:**
- `$modelClass`: The model class name
- `$id`: The model ID

#### `method(string $method)`
Sets the HTTP method for the form.

**Parameters:**
- `$method`: HTTP method (GET, POST, PUT, PATCH, DELETE)

#### `action(string $action)`
Sets the form action URL.

**Parameters:**
- `$action`: The form submission URL

#### `formAttribute(string $key, string $value)`
Adds a custom attribute to the form element.

**Parameters:**
- `$key`: Attribute name
- `$value`: Attribute value

#### `handle(Request $request, array $rules = [], array $messages = [])`
Handles form submission with validation and model saving.

**Parameters:**
- `$request`: The HTTP request
- `$rules`: Validation rules
- `$messages`: Custom validation messages

**Returns:** Array with success status and data

#### `getModel()`
Gets the currently bound model.

**Returns:** Model instance or null

### Form Rendering

The form automatically includes:
- CSRF protection token
- Method spoofing for PUT/PATCH/DELETE requests
- Proper form attributes
- Submit button
- Error handling

## Best Practices

### 1. Validation Rules

```php
// Define comprehensive validation rules
$rules = [
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users,email,' . $id,
    'password' => 'nullable|string|min:8|confirmed',
    'phone' => 'nullable|string|max:20|regex:/^[0-9\-\+\(\)\s]+$/',
    'role' => 'required|in:user,editor,admin',
    'is_active' => 'boolean',
    'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
];
```

### 2. Custom Validation Messages

```php
$messages = [
    'name.required' => 'Please enter the user\'s full name.',
    'email.required' => 'Please enter an email address.',
    'email.unique' => 'This email address is already registered.',
    'password.required' => 'Please enter a password.',
    'password.min' => 'Password must be at least 8 characters.',
    'password.confirmed' => 'Password confirmation does not match.',
    'phone.regex' => 'Please enter a valid phone number.',
    'avatar.image' => 'The file must be an image.',
    'avatar.max' => 'The image must not be larger than 2MB.'
];
```

### 3. Error Handling

```php
public function store(Request $request)
{
    try {
        $result = $form->handle($request, $rules, $messages);
        
        if ($result['success']) {
            // Additional logic after successful save
            $user = $result['model'];
            
            // Send welcome email
            $user->notify(new WelcomeNotification());
            
            // Log the action
            activity()->log("Created user: {$user->name}");
            
            return redirect()->with('success', 'User created successfully!');
        }
        
        return back()->withErrors($result['errors']);
        
    } catch (\Exception $e) {
        \Log::error('User creation failed: ' . $e->getMessage());
        return back()->withErrors(['general' => 'An error occurred while creating the user.']);
    }
}
```

### 4. Model Events

```php
// In your model
class User extends Authenticatable
{
    protected static function booted()
    {
        static::creating(function ($user) {
            $user->email_verified_at = now();
        });
        
        static::updating(function ($user) {
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }
        });
    }
}
```

## Examples

See the `ModelBindingController` for complete working examples:
- Create form: `/model-binding`
- Advanced form: `/model-binding/advanced`
- Edit form: `/model-binding/{id}/edit`

## Benefits

1. **Automatic Data Population**: Fields automatically populate with model data
2. **Built-in Validation**: Comprehensive validation with custom messages
3. **CSRF Protection**: Automatic CSRF token inclusion
4. **Method Spoofing**: Support for PUT/PATCH/DELETE requests
5. **Error Handling**: Graceful error handling and display
6. **Type Safety**: Type-safe model binding and data handling
7. **Relationship Support**: Support for nested model relationships
8. **Performance**: Efficient form rendering and data processing 