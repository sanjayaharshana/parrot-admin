@extends('userpanel::components.layouts.master')

@section('title', 'Callable Options Example')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Callable Options Example</h1>
            <p class="text-gray-600">Demonstrating dynamic option generation for select and radio fields using callable functions.</p>
        </div>

        <!-- Navigation -->
        <div class="mb-6">
            <nav class="flex space-x-4">
                <a href="{{ route('userpanel.callable-options') }}" 
                   class="px-4 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition-colors">
                    Basic Example
                </a>
                <a href="{{ route('userpanel.callable-options.advanced') }}" 
                   class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors">
                    Advanced Example
                </a>
            </nav>
        </div>

        <!-- Info Alert -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">Callable Options Feature</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>This example shows how to use callable functions for dynamic option generation in select and radio fields. This allows for database queries, API calls, or complex logic to generate options at runtime.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Code Example -->
        <div class="bg-gray-900 rounded-lg p-6 mb-6">
            <h3 class="text-white font-semibold mb-4">Example Code:</h3>
            <pre class="text-green-400 text-sm overflow-x-auto"><code>// Static options (traditional way)
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

// Dynamic options with logic
$form->radio()
    ->name('privilege')
    ->label('Privilege Level')
    ->options(function () {
        $privileges = [
            1 => 'Public (Everyone can view)',
            2 => 'Private (Only members)',
            3 => 'Restricted (Admin only)'
        ];
        
        // Add conditional logic
        if (auth()->user() && auth()->user()->isAdmin()) {
            $privileges[4] = 'Super Admin (System only)';
        }
        
        return $privileges;
    })
    ->required();</code></pre>
        </div>

        <!-- Rendered Form -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Rendered Form</h2>
            </div>
            <div class="p-6">
                {!! $layout !!}
            </div>
        </div>

        <!-- Benefits -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                <div class="text-blue-500 mb-3">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Database Integration</h3>
                <p class="text-gray-600">Easily integrate with database queries to populate options dynamically from your models and relationships.</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                <div class="text-green-500 mb-3">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Dynamic Logic</h3>
                <p class="text-gray-600">Add conditional logic, user permissions, or business rules to determine which options should be available.</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                <div class="text-purple-500 mb-3">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Performance</h3>
                <p class="text-gray-600">Options are generated only when needed, allowing for lazy loading and better performance with large datasets.</p>
            </div>
        </div>

        <!-- Use Cases -->
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Common Use Cases</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">User Management</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Role selection based on user permissions</li>
                        <li>• Department assignment from database</li>
                        <li>• Manager selection from team members</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Content Management</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Category selection from active categories</li>
                        <li>• Author assignment from available users</li>
                        <li>• Tag selection from existing tags</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Settings & Preferences</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Timezone selection with current offset</li>
                        <li>• Language options with user's locale</li>
                        <li>• Theme selection with premium features</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Business Logic</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Status options based on workflow</li>
                        <li>• Priority levels based on user role</li>
                        <li>• Notification preferences with conditions</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 