# Tab System Quick Reference

## Basic Implementation

```php
// 1. Enable tabs
->enableTabs()

// 2. Create tabs with method chaining
->tab('id', 'Label', 'fa fa-icon')
    ->field('name')->required()->searchable()
    ->field('email')->required()->searchable()
    ->end()

// 3. Always end tabs
->end()
```

## Available Tab Methods

### Field Types
```php
->text('field_name')           // Text input
->email('field_name')          // Email input
->password('field_name')       // Password input
->textarea('field_name')       // Textarea
->select('field_name')         // Select dropdown
->checkbox('field_name')       // Checkbox
->radio('field_name')          // Radio button
->file('field_name')           // File upload
->date('field_name')           // Date picker
->datetime('field_name')       // DateTime picker
->number('field_name')         // Number input
```

### Content Elements
```php
->divider('Section Title')     // Horizontal divider with text
->alert('Message', 'type')     // Alert box (info/warning/error/success)
->customHtml('HTML', 'Title', 'CSS Classes') // Custom HTML content
```

## Method Chaining

All TabBuilder methods return the TabBuilder instance, allowing method chaining:

```php
->tab('basic', 'Basic Info', 'fa fa-info')
    ->text('name')->required()->searchable()->sortable()
    ->email('email')->required()->searchable()->sortable()
    ->password('password')->required()->rules(['min:8'])
    ->end()
```

## Complete Example

```php
protected function makeResource(): ResourceService
{
    return (new ResourceService(Model::class, 'route'))
        ->enableTabs()
        
        ->tab('basic', 'Basic Info', 'fa fa-info')
            ->text('name')->required()
            ->text('email')->required()
            ->end()
            
        ->tab('details', 'Details', 'fa fa-plus')
            ->textarea('description')
            ->select('category')
            ->end()
            
        ->tab('settings', 'Settings', 'fa fa-cog')
            ->checkbox('active')
            ->date('expiry_date')
            ->end();
}
```

## Field Configuration

```php
->text('field_name')
    ->required()           // Make field required
    ->searchable()         // Enable search
    ->sortable()           // Enable sorting
    ->rules(['max:255'])   // Validation rules
    ->placeholder('Enter text') // Placeholder text
    ->help('Help text')    // Help text below field
```

## Validation

```php
->text('email')
    ->rules(['required', 'email', 'max:255'])
    ->messages([
        'required' => 'Email is required',
        'email' => 'Must be valid email'
    ])
```

## Common Patterns

### User Management
```php
->tab('profile', 'Profile', 'fa fa-user')
    ->text('first_name')->required()
    ->text('last_name')->required()
    ->email('email')->required()
    ->end()

->tab('settings', 'Settings', 'fa fa-cog')
    ->checkbox('notifications')
    ->select('timezone')
    ->end()
```

### Product Management
```php
->tab('basic', 'Basic Info', 'fa fa-info')
    ->text('name')->required()
    ->textarea('description')
    ->select('category')->required()
    ->end()

->tab('pricing', 'Pricing', 'fa fa-dollar')
    ->number('price')->required()
    ->number('cost')
    ->number('stock')->required()
    ->end()

->tab('media', 'Media', 'fa fa-image')
    ->file('main_image')
    ->file('gallery')->multiple()
    ->end()
```

## Best Practices

1. **Group logically**: Related fields in same tab
2. **Limit fields**: 5-10 fields per tab max
3. **Clear names**: Use descriptive tab labels
4. **Icons**: Include relevant FontAwesome icons
5. **Required first**: Put required fields in first tab
6. **End tabs**: Always call `->end()` after each tab
7. **Method chaining**: Use chaining for cleaner code

## Troubleshooting

- **Tabs not showing**: Check `->enableTabs()` is called
- **Fields missing**: Ensure `->end()` is called for each tab
- **Styling issues**: Verify Alpine.js is included
- **Validation errors**: Check field rules and names
- **Method chaining errors**: Remember TabBuilder methods return TabBuilder instance

## CSS Classes

```css
.tabs-container     /* Main tab container */
.tab-button        /* Individual tab buttons */
.tab-panel         /* Tab content panels */
.tab-active        /* Active tab styling */
```

## JavaScript Events

```javascript
// Tab change event
Alpine.data('tabs', () => ({
    activeTab: 'basic',
    switchTab(tab) {
        this.activeTab = tab;
    }
}))
```
