# Custom Content Documentation

## Overview

The custom content feature allows you to add custom HTML, Blade views, and components directly within your layouts. This provides maximum flexibility for creating complex forms with custom content, interactive elements, and reusable components.

## Quick Start

```php
use Modules\UserPanel\Services\FormService;
use Modules\UserPanel\Services\LayoutService;

$form = new FormService();
$layout = new LayoutService();
$layout->setFormService($form);

// Add custom HTML
$layout->html('<div class="alert">Custom HTML content</div>');

// Add custom Blade view
$layout->view('components.stats', ['data' => $stats]);

// Add custom component
$layout->component('progress-bar', ['progress' => 75]);

// Add to existing components
$layout->section('Info', function ($form, $section) {
    $section->addField($form->text()->name('title'));
    $section->addHtml('<div class="alert">Custom alert</div>');
    $section->addView('components.chart', ['data' => $chartData]);
});
```

## Basic Usage

### Custom HTML

```php
// Standalone HTML
$layout->html('
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h3 class="text-blue-900 font-medium">Custom Notice</h3>
        <p class="text-blue-700">This is custom HTML content.</p>
    </div>
');

// HTML with custom classes
$layout->html('
    <button type="button" onclick="alert(\'Hello!\')" class="btn">
        Click Me
    </button>
', 'text-center mt-4');
```

### Custom Blade Views

```php
// Simple view
$layout->view('components.notice', [
    'message' => 'This is a notice message',
    'type' => 'info'
]);

// Complex view with data
$layout->view('components.chart', [
    'title' => 'Sales Chart',
    'data' => [
        'labels' => ['Jan', 'Feb', 'Mar'],
        'values' => [10, 20, 15]
    ],
    'options' => [
        'type' => 'bar',
        'colors' => ['#3B82F6', '#10B981', '#F59E0B']
    ]
]);
```

### Custom Components

```php
// Blade component
$layout->component('progress-bar', [
    'progress' => 75,
    'label' => 'Project Progress',
    'color' => 'blue'
]);

// Component with complex data
$layout->component('data-table', [
    'headers' => ['Name', 'Email', 'Role'],
    'rows' => $users,
    'actions' => ['edit', 'delete'],
    'pagination' => true
]);
```

## Adding to Existing Components

### Within Sections

```php
$layout->section('Project Details', function ($form, $section) {
    $section->addField(
        $form->text()
            ->name('project_name')
            ->label('Project Name')
            ->required()
    );
    
    // Add custom HTML
    $section->addHtml('
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
            <div class="flex">
                <svg class="h-5 w-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-medium text-yellow-800">Important Notice</span>
            </div>
            <p class="mt-2 text-sm text-yellow-700">
                This project is currently in review phase.
            </p>
        </div>
    ');
    
    // Add custom view
    $section->addView('components.project-stats', [
        'stats' => [
            'progress' => 65,
            'tasks' => 24,
            'completed' => 16
        ]
    ]);
    
    $section->addField(
        $form->textarea()
            ->name('description')
            ->label('Description')
    );
});
```

### Within Cards

```php
$layout->card('Additional Information', function ($form, $card) {
    $card->addField(
        $form->text()
            ->name('tags')
            ->label('Tags')
    );
    
    // Add custom component
    $card->addComponent('tag-input', [
        'suggestions' => ['Laravel', 'PHP', 'Vue.js', 'Tailwind'],
        'maxTags' => 5
    ]);
    
    // Add custom HTML
    $card->addHtml('
        <div class="flex space-x-2 mt-4">
            <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                Save Draft
            </button>
            <button type="button" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm">
                Publish
            </button>
        </div>
    ');
});
```

### Within Columns

```php
$layout->row()
    ->column('1/2', function ($form, $column) {
        $column->addField(
            $form->text()
                ->name('first_name')
                ->label('First Name')
                ->required()
        );
        
        // Add custom view
        $column->addView('components.user-avatar', [
            'user' => auth()->user(),
            'size' => 'large'
        ]);
        
        $column->addField(
            $form->text()
                ->name('last_name')
                ->label('Last Name')
                ->required()
        );
    })
    ->column('1/2', function ($form, $column) {
        // Add custom HTML with JavaScript
        $column->addHtml('
            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Quick Actions</h4>
                <div class="space-y-2">
                    <button type="button" onclick="copyToClipboard(\'{{ auth()->user()->email }}\')" 
                            class="w-full text-left text-sm text-blue-600 hover:text-blue-700">
                        Copy Email Address
                    </button>
                    <button type="button" onclick="generatePassword()" 
                            class="w-full text-left text-sm text-blue-600 hover:text-blue-700">
                        Generate Password
                    </button>
                </div>
            </div>
        ');
        
        $column->addField(
            $form->email()
                ->name('email')
                ->label('Email')
                ->required()
        );
    });
```

## Advanced Examples

### Interactive Dashboard Widget

```php
$layout->html('
    <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Real-time Statistics</h3>
            <button type="button" onclick="refreshStats()" 
                    class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                Refresh
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4" id="stats-container">
            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-blue-600">Total Users</p>
                        <p class="text-lg font-semibold text-blue-900" id="total-users">1,234</p>
                    </div>
                </div>
            </div>
            <div class="bg-green-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-green-600">Active Projects</p>
                        <p class="text-lg font-semibold text-green-900" id="active-projects">56</p>
                    </div>
                </div>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-purple-600">Revenue</p>
                        <p class="text-lg font-semibold text-purple-900" id="revenue">$12,345</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    function refreshStats() {
        // AJAX call to refresh statistics
        fetch(\'/api/stats\')
            .then(response => response.json())
            .then(data => {
                document.getElementById(\'total-users\').textContent = data.users;
                document.getElementById(\'active-projects\').textContent = data.projects;
                document.getElementById(\'revenue\').textContent = data.revenue;
            });
    }
    </script>
');
```

### Complex Form with Custom Components

```php
$layout->section('Project Information', function ($form, $section) {
    $section->addField(
        $form->text()
            ->name('project_name')
            ->label('Project Name')
            ->required()
    );
    
    // Add custom file upload component
    $section->addComponent('file-upload', [
        'name' => 'project_files',
        'multiple' => true,
        'accept' => '.pdf,.doc,.docx,.jpg,.png',
        'maxSize' => '10MB',
        'preview' => true
    ]);
    
    $section->addField(
        $form->textarea()
            ->name('description')
            ->label('Description')
            ->required()
    );
    
    // Add custom rich text editor
    $section->addComponent('rich-text-editor', [
        'name' => 'content',
        'placeholder' => 'Enter project content...',
        'toolbar' => ['bold', 'italic', 'link', 'list', 'image'],
        'height' => '300px'
    ]);
});

$layout->section('Team Members', function ($form, $section) {
    // Add custom team member selector
    $section->addComponent('team-selector', [
        'name' => 'team_members',
        'users' => User::active()->get(),
        'roles' => ['Developer', 'Designer', 'Manager', 'Tester'],
        'maxMembers' => 10
    ]);
    
    // Add custom role assignment
    $section->addView('components.role-assignment', [
        'project' => $project,
        'availableRoles' => Role::all()
    ]);
});
```

## API Reference

### LayoutService Methods

#### `html(string $html, string $classes = '')`
Adds custom HTML content to the layout.

**Parameters:**
- `$html`: HTML string to add
- `$classes`: Optional CSS classes for wrapper div

**Returns:** LayoutHtml instance

#### `view(string $view, array $data = [], string $classes = '')`
Adds a Blade view to the layout.

**Parameters:**
- `$view`: Blade view name
- `$data`: Data to pass to the view
- `$classes`: Optional CSS classes for wrapper div

**Returns:** LayoutView instance

#### `component(string $component, array $data = [], string $classes = '')`
Adds a Blade component to the layout.

**Parameters:**
- `$component`: Component name (without 'components.' prefix)
- `$data`: Data to pass to the component
- `$classes`: Optional CSS classes for wrapper div

**Returns:** LayoutComponent instance

### Layout Component Methods

All layout components (LayoutColumn, LayoutSection, LayoutCard, etc.) support these methods:

#### `addHtml(string $html)`
Adds custom HTML to the component.

#### `addView(string $view, array $data = [])`
Adds a Blade view to the component.

#### `addComponent(string $component, array $data = [])`
Adds a Blade component to the component.

## Error Handling

The system includes built-in error handling for view and component rendering:

```php
// If a view doesn't exist or has errors, it will render an error message
$layout->view('non-existent-view', ['data' => 'test']);

// Outputs:
// <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-700">
//     <p class="font-medium">Error rendering view: non-existent-view</p>
//     <p class="text-sm">View [non-existent-view] not found.</p>
// </div>
```

## Best Practices

### 1. Component Organization

```php
// Create reusable components
// resources/views/components/progress-bar.blade.php
<div class="bg-gray-200 rounded-full h-2">
    <div class="bg-blue-500 h-2 rounded-full transition-all duration-300" 
         style="width: {{ $progress }}%"></div>
</div>
<div class="text-sm text-gray-600 mt-1">{{ $label }}: {{ $progress }}%</div>

// Use in layout
$layout->component('progress-bar', [
    'progress' => 75,
    'label' => 'Project Progress'
]);
```

### 2. Data Preparation

```php
// Prepare data in controller
public function index()
{
    $stats = [
        'users' => User::count(),
        'projects' => Project::active()->count(),
        'revenue' => Order::sum('amount')
    ];
    
    $layout = new LayoutService();
    $layout->view('components.dashboard-stats', ['stats' => $stats]);
    
    return view('dashboard', ['layout' => $layout->render()]);
}
```

### 3. Conditional Content

```php
$layout->section('Advanced Settings', function ($form, $section) {
    $section->addField($form->text()->name('setting1'));
    
    // Add conditional content based on user permissions
    if (auth()->user()->isAdmin()) {
        $section->addComponent('admin-panel', [
            'settings' => AdminSetting::all()
        ]);
    }
    
    if (auth()->user()->isPremium()) {
        $section->addView('components.premium-features', [
            'features' => PremiumFeature::all()
        ]);
    }
});
```

### 4. Performance Optimization

```php
// Cache expensive components
$layout->view('components.expensive-chart', [
    'data' => Cache::remember('chart_data', 3600, function () {
        return ChartData::complexCalculation();
    })
]);

// Lazy load components
$layout->component('lazy-table', [
    'url' => route('api.data'),
    'columns' => ['name', 'email', 'role']
]);
```

## Examples

See the `CustomContentController` for complete working examples:
- Basic example: `/custom-content`
- Advanced example: `/custom-content/advanced`

## Benefits

1. **Maximum Flexibility**: Add any HTML, JavaScript, or CSS to your layouts
2. **Component Reusability**: Create reusable Blade components for common UI elements
3. **Separation of Concerns**: Keep complex UI logic in separate view files
4. **Interactive Elements**: Add JavaScript, AJAX, and dynamic content
5. **Third-party Integration**: Easily integrate external libraries and widgets
6. **Maintainability**: Organize complex layouts into manageable components 