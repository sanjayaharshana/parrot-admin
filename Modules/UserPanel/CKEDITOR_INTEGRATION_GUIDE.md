# CKEditor Integration Guide

## Overview

This guide explains how to use CKEditor with your Laravel form system. CKEditor provides a rich text editing experience with features like formatting, tables, images, and more.

## Installation

CKEditor is already installed and configured in your project. The necessary packages are:

- `@ckeditor/ckeditor5-build-classic` - The main CKEditor package
- Custom CSS and JavaScript for integration

## Quick Start

### 1. Basic Rich Text Field

```php
use Modules\UserPanel\Services\Form\FormService;
use Modules\UserPanel\Services\LayoutService;

$form = new FormService();
$layout = new LayoutService();
$layout->setFormService($form);

$layout->section('Content', 'Enter your content below.')
    ->addField(
        $form->richText()
            ->name('content')
            ->label('Content')
            ->placeholder('Start writing your content...')
            ->required()
    );
```

### 2. Enable CKEditor on Existing Textarea

```php
$layout->addField(
    $form->textarea()
        ->name('description')
        ->label('Description')
        ->ckeditor(true) // Enable CKEditor manually
);
```

## Available Methods

### FormService

```php
// Create a rich text field with CKEditor enabled
$form->richText()
    ->name('content')
    ->label('Content')
    ->required();

// Create a regular textarea and enable CKEditor
$form->textarea()
    ->name('content')
    ->label('Content')
    ->ckeditor(true);
```

### Tab Class

```php
// Add rich text field to a tab
$tab->richText('content', [
    'label' => 'Content',
    'required' => true
]);

// Add textarea and enable CKEditor
$tab->textarea('content')
    ->label('Content')
    ->ckeditor(true);
```

### TabBuilder

```php
// Add rich text field to a tab
$tabBuilder->richText('content', [
    'label' => 'Content',
    'required' => true
]);
```

### ResourceService

```php
// Define a rich text field
$this->richText('content', [
    'label' => 'Content',
    'required' => true
]);

// Enable CKEditor on existing textarea field
$this->textarea('description')
    ->ckeditor(true)
    ->required();
```

## Field Configuration

### Basic Configuration

```php
$form->richText()
    ->name('content')
    ->label('Content')
    ->placeholder('Enter your content here...')
    ->required()
    ->value($existingContent);
```

### Advanced Configuration

```php
$form->richText()
    ->name('content')
    ->label('Content')
    ->required()
    ->height(400) // Set custom height in pixels
    ->ckeditor(true); // Enable CKEditor
```

### Height Configuration

You can set custom heights for CKEditor fields using the `height()` method:

```php
// Set height to 300px
->richText('content')->height(300)

// Set height to 500px
->textarea('description')->ckeditor(true)->height(500)

// Combine with other methods
->richText('content')
    ->height(400)
    ->required()
    ->label('Main Content')
```

## CKEditor Features

### Available Toolbar Items

The default CKEditor configuration includes:

- **Text Formatting**: Bold, italic, headings, lists
- **Content Management**: Links, images, tables
- **Layout**: Indentation, blockquotes, code blocks
- **Media**: Image upload, media embedding
- **Utilities**: Undo/redo, table editing

### Customizing Toolbar

You can customize the toolbar by modifying the `ckeditor.js` file:

```javascript
const ckeditorConfig = {
    toolbar: {
        items: [
            'heading',
            '|',
            'bold',
            'italic',
            'link',
            'bulletedList',
            'numberedList',
            '|',
            'outdent',
            'indent',
            '|',
            'imageUpload',
            'blockQuote',
            'insertTable',
            'mediaEmbed',
            'undo',
            'redo'
        ]
    },
    // ... other configuration
};
```

## Integration Examples

### 1. Blog Post Form

```php
public function createForm()
{
    $layout = $this->layoutService;
    $layout->setFormService($this->form);
    
    $layout->section('Post Information')
        ->addField(
            $this->form->text()
                ->name('title')
                ->label('Title')
                ->required()
        )
        ->addField(
            $this->form->textarea()
                ->name('excerpt')
                ->label('Excerpt')
                ->placeholder('Brief summary of the post')
        );
    
    $layout->section('Post Content')
        ->addField(
            $this->form->richText()
                ->name('content')
                ->label('Content')
                ->placeholder('Write your blog post content...')
                ->required()
        );
    
    return $layout->render();
}
```

### 2. Product Description with Tabs

```php
public function createForm()
{
    $layout = $this->layoutService;
    $layout->setFormService($this->form);
    
    // Enable tabs
    $this->form->enableTabs();
    
    // General Information Tab
    $this->form->addTab('general', 'General Information')
        ->addField(
            $this->form->text()
                ->name('name')
                ->label('Product Name')
                ->required()
        )
        ->addField(
            $this->form->number()
                ->name('price')
                ->label('Price')
                ->required()
        );
    
    // Description Tab
    $this->form->addTab('description', 'Description')
        ->addField(
            $this->form->richText()
                ->name('description')
                ->label('Product Description')
                ->placeholder('Describe your product in detail...')
                ->required()
        );
    
    return $layout->render();
}
```

### 3. Documentation Page

```php
public function createForm()
{
    $layout = $this->layoutService;
    $layout->setFormService($this->form);
    
    $layout->section('Page Information')
        ->addField(
            $this->form->text()
                ->name('title')
                ->label('Page Title')
                ->required()
        )
        ->addField(
            $this->form->select()
                ->name('category_id')
                ->label('Category')
                ->options($categories)
                ->required()
        );
    
    $layout->section('Page Content')
        ->addField(
            $this->form->richText()
                ->name('content')
                ->label('Content')
                ->placeholder('Write your documentation content...')
                ->required()
        );
    
    return $layout->render();
}
```

## Handling Form Submission

### Controller Method

```php
public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'excerpt' => 'nullable|string',
    ]);

    // The content field will contain HTML from CKEditor
    $content = $request->input('content');
    
    // Save to database
    $post = Post::create([
        'title' => $request->input('title'),
        'content' => $content, // HTML content
        'excerpt' => $request->input('excerpt'),
    ]);

    return redirect()->route('posts.index')
        ->with('success', 'Post created successfully!');
}
```

### Displaying Content

When displaying CKEditor content, make sure to render it as HTML:

```blade
{{-- In your Blade template --}}
<div class="post-content">
    {!! $post->content !!}
</div>
```

**Important**: Always use `{!! !!}` (unescaped) for CKEditor content, not `{{ }}` (escaped), as the content contains HTML.

## Styling and Customization

### CSS Classes

CKEditor automatically applies these CSS classes:

- `.ckeditor-container` - Main container
- `.ck-editor__editable` - Editable content area
- `.ck-toolbar` - Toolbar styling

### Custom Styling

You can customize the appearance by modifying `resources/css/ckeditor.css`:

```css
.ckeditor-container {
    border: 2px solid #e5e7eb;
    border-radius: 0.5rem;
}

.ckeditor-container .ck-editor__editable {
    min-height: 300px;
    max-height: 600px;
}
```

## Best Practices

### 1. Content Validation

Always validate CKEditor content on the server side:

```php
$request->validate([
    'content' => 'required|string|max:10000', // Set appropriate max length
]);
```

### 2. Content Sanitization

Consider using HTML purifier for additional security:

```php
use HTMLPurifier;
use HTMLPurifier_Config;

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
$cleanContent = $purifier->purify($request->input('content'));
```

### 3. Performance

- Set appropriate max lengths for content fields
- Consider lazy loading for large content
- Use database indexing for searchable content

### 4. Accessibility

- Ensure proper heading hierarchy in content
- Use descriptive alt text for images
- Maintain good color contrast

## Troubleshooting

### Common Issues

1. **CKEditor not loading**: Check browser console for JavaScript errors
2. **Content not saving**: Verify the textarea has a unique ID
3. **Styling issues**: Check if CSS is properly loaded
4. **Form validation errors**: Ensure content field is included in validation rules
5. **Method ckeditor does not exist**: Make sure you're using the correct method:
   - For `ResourceService`: Use `->textarea('field')->ckeditor(true)`
   - For `FormService`: Use `->textarea()->ckeditor(true)` or `->richText()`
   - For `Tab` class: Use `->textarea('field')->ckeditor(true)` or `->richText('field')`

### Debug Mode

Enable debug mode in your `.env` file:

```env
APP_DEBUG=true
```

### Browser Compatibility

CKEditor 5 supports:
- Chrome 60+
- Firefox 60+
- Safari 12+
- Edge 79+

## Advanced Configuration

### Custom CKEditor Build

For advanced features, you can create a custom CKEditor build:

1. Install the CKEditor builder:
```bash
npm install -g @ckeditor/ckeditor5-builder
```

2. Create a custom build configuration
3. Build and replace the default build

### Plugin Development

You can develop custom CKEditor plugins for specific functionality:

```javascript
// Example custom plugin
function CustomPlugin(editor) {
    editor.model.schema.extend('$text', { allowAttributes: 'customAttribute' });
    
    editor.conversion.attributeToElement({
        model: 'customAttribute',
        view: 'span',
        converterPriority: 'high'
    });
}
```

## Support and Resources

- [CKEditor 5 Documentation](https://ckeditor.com/docs/ckeditor5/)
- [CKEditor 5 API Reference](https://ckeditor.com/docs/ckeditor5/latest/api/)
- [CKEditor 5 Examples](https://ckeditor.com/docs/ckeditor5/latest/examples/)

## Demo

Visit `/ckeditor-demo` in your application to see CKEditor in action with various examples and usage patterns.

## Height Configuration Examples

### Using the height() Method

The `height()` method allows you to set custom heights for CKEditor fields:

```php
// In ResourceService
$this->richText('content')->height(300);

// In FormService
$form->richText()
    ->name('content')
    ->height(400)
    ->required();

// In TabBuilder
$tab->richText('content')->height(500);

// Combine with other methods
$this->richText('content')
    ->height(350)
    ->required()
    ->label('Main Content');
```

### Height Values

- **Small editors**: 200-300px (for brief content)
- **Medium editors**: 350-450px (for standard content)
- **Large editors**: 500-700px (for detailed content)
- **Custom sizes**: Any pixel value you prefer

### Responsive Heights

The height setting works well with responsive design:

```php
// Different heights for different content types
$this->richText('excerpt')->height(200);      // Short excerpt
$this->richText('content')->height(400);      // Main content
$this->richText('details')->height(600);      // Detailed information
```
