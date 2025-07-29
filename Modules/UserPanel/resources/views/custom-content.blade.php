@extends('userpanel::components.layouts.master')

@section('title', 'Custom Content Example')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Custom Content Example</h1>
            <p class="text-gray-600">Demonstrating custom HTML and Blade views within layouts for maximum flexibility.</p>
        </div>

        <!-- Navigation -->
        <div class="mb-6">
            <nav class="flex space-x-4">
                <a href="{{ route('userpanel.custom-content') }}" 
                   class="px-4 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition-colors">
                    Basic Example
                </a>
                <a href="{{ route('userpanel.custom-content.advanced') }}" 
                   class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors">
                    Advanced Example
                </a>
            </nav>
        </div>

        <!-- Info Alert -->
        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-purple-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-purple-800">Custom Content Features</h3>
                    <div class="mt-2 text-sm text-purple-700">
                        <p>This example shows how to add custom HTML, Blade views, and components within your layouts. This provides maximum flexibility for creating complex forms with custom content.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Code Example -->
        <div class="bg-gray-900 rounded-lg p-6 mb-6">
            <h3 class="text-white font-semibold mb-4">Example Code:</h3>
            <pre class="text-green-400 text-sm overflow-x-auto"><code>// Add custom HTML to layout
$layout->html('
    &lt;div class="bg-blue-50 border border-blue-200 rounded-lg p-4"&gt;
        &lt;h3&gt;Custom HTML Content&lt;/h3&gt;
        &lt;p&gt;This is custom HTML added to the layout.&lt;/p&gt;
    &lt;/div&gt;
');

// Add custom Blade view
$layout->view('components.custom-stats', [
    'stats' => [
        ['label' => 'Total Users', 'value' => '1,234'],
        ['label' => 'Active Projects', 'value' => '56']
    ]
]);

// Add custom HTML to existing components
$section->addHtml('&lt;div class="alert"&gt;Custom alert&lt;/div&gt;');
$section->addView('components.progress-bar', ['progress' => 75]);

// Add custom component
$layout->component('progress-bar', [
    'progress' => 75,
    'label' => 'Project Progress'
]);</code></pre>
        </div>

        <!-- Rendered Form -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Rendered Form with Custom Content</h2>
            </div>
            <div class="p-6">
                {!! $layout !!}
            </div>
        </div>

        <!-- Features -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                <div class="text-blue-500 mb-3">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Custom HTML</h3>
                <p class="text-gray-600">Add any HTML content directly to your layouts, including JavaScript, CSS, and interactive elements.</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                <div class="text-green-500 mb-3">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Blade Views</h3>
                <p class="text-gray-600">Include complete Blade views with data binding, making it easy to reuse complex components.</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                <div class="text-purple-500 mb-3">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Components</h3>
                <p class="text-gray-600">Use Blade components for reusable UI elements that can be easily maintained and updated.</p>
            </div>
        </div>

        <!-- Use Cases -->
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Common Use Cases</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Dashboard Widgets</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Statistics cards with real-time data</li>
                        <li>• Charts and graphs</li>
                        <li>• Progress indicators</li>
                        <li>• Activity feeds</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Form Enhancements</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Custom validation messages</li>
                        <li>• Help text and tooltips</li>
                        <li>• Conditional content</li>
                        <li>• File upload previews</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Interactive Elements</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Custom buttons and actions</li>
                        <li>• Modal triggers</li>
                        <li>• AJAX content loading</li>
                        <li>• Real-time updates</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Content Blocks</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Rich text editors</li>
                        <li>• Media galleries</li>
                        <li>• Social media embeds</li>
                        <li>• Third-party integrations</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 