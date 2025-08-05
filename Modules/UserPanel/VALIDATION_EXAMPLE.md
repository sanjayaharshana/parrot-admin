# FormService Validation System Examples

## Overview

This document demonstrates how to use the new integrated validation system in FormService to eliminate duplicate validation between controllers and form services.

## Before (Duplicate Validation)

### Controller
```php
public function store(Request $request)
{
    // Validation in controller
    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
        'title' => 'required|string|max:255',
        'desc' => 'required|string',
        'uploader_id' => 'required|exists:users,id',
        'path' => 'nullable|string|max:255'
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    // Manual model creation and saving
    $model = new $this->model();
    $model->title = $request->title;
    $model->desc = $request->desc;
    $model->uploader_id = $request->uploader_id;
    $model->path = $request->path;
    $model->save();

    return redirect()->route($this->getRouteName() . '.index')
        ->with('success', 'Record created successfully!');
}
```

### Form Service
```php
// No validation rules stored in form service
$form = new FormService();
$form->text()->name('title')->label('Title')->required();
$form->textarea()->name('desc')->label('Description')->required();
```

## After (Integrated Validation)

### Controller
```php
public function store(Request $request)
{
    // Simple validation handling - no duplicate rules!
    $result = $this->form->handle($request);

    if (!$result['success']) {
        return redirect()->back()
            ->withErrors($result['errors'])
            ->withInput();
    }

    return redirect()->route($this->getRouteName() . '.index')
        ->with('success', 'Record created successfully!');
}
```

### Form Service
```php
// Validation rules stored in form service
$form = new FormService();

// Set validation rules
$form->setValidationRules([
    'title' => 'required|string|max:255',
    'desc' => 'required|string',
    'uploader_id' => 'required|exists:users,id',
    'path' => 'nullable|string|max:255'
]);

// Set custom validation messages
$form->setValidationMessages([
    'title.required' => 'Please enter a title.',
    'title.max' => 'Title must not exceed 255 characters.',
    'desc.required' => 'Please enter a description.',
    'uploader_id.required' => 'Please select an uploader.',
    'uploader_id.exists' => 'The selected uploader is invalid.'
]);

// Add form fields
$form->text()->name('title')->label('Title')->required();
$form->textarea()->name('desc')->label('Description')->required();
```

## Benefits

1. **No Duplicate Validation**: Validation rules are defined once in the FormService
2. **Better Maintainability**: Changes to validation rules only need to be made in one place
3. **Cleaner Controllers**: Controllers focus on business logic, not validation
4. **Consistent Validation**: All forms use the same validation system
5. **User-Friendly**: Custom validation messages provide better user experience

## Advanced Examples

### Dynamic Validation Rules

```php
public function createForm($mode = 'create')
{
    $form = new FormService();
    
    // Set base validation rules
    $form->setValidationRules([
        'title' => 'required|string|max:255',
        'desc' => 'required|string',
        'uploader_id' => 'required|exists:users,id',
        'path' => 'nullable|string|max:255'
    ]);

    // Add conditional validation based on mode
    if ($mode === 'edit') {
        $form->addValidationRule('title', 'unique:products,title,' . $this->model->id);
    } else {
        $form->addValidationRule('title', 'unique:products,title');
    }

    // Add form fields...
    return $form->renderForm();
}
```

### Validation with Model Binding

```php
public function update(Request $request, $id)
{
    $model = $this->model::findOrFail($id);
    
    // Bind model to form service
    $this->form->bindModel($model);
    
    // Handle validation and update
    $result = $this->form->handle($request);
    
    if (!$result['success']) {
        return redirect()->back()
            ->withErrors($result['errors'])
            ->withInput();
    }
    
    return redirect()->route($this->getRouteName() . '.index')
        ->with('success', 'Record updated successfully!');
}
```

### Custom Validation Messages

```php
$form->setValidationMessages([
    'title.required' => 'Please enter a title for this item.',
    'title.max' => 'The title cannot be longer than 255 characters.',
    'title.unique' => 'This title is already taken. Please choose a different one.',
    'desc.required' => 'Please provide a description.',
    'uploader_id.required' => 'Please select who will upload this item.',
    'uploader_id.exists' => 'The selected uploader does not exist in our system.',
    'path.url' => 'Please enter a valid URL for the path.',
    'path.max' => 'The path cannot be longer than 255 characters.'
]);
```

## Migration Guide

To migrate from the old validation system to the new integrated system:

1. **Move validation rules** from controllers to FormService
2. **Update controllers** to use `$this->form->handle($request)`
3. **Add custom validation messages** for better user experience
4. **Test thoroughly** to ensure validation still works correctly

## Best Practices

1. **Define validation rules in FormService**: Keep all validation logic in one place
2. **Use custom messages**: Provide user-friendly error messages
3. **Test validation thoroughly**: Ensure all edge cases are covered
4. **Document validation rules**: Add comments for complex validation logic
5. **Use consistent naming**: Follow Laravel naming conventions for validation rules 