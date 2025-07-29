@extends('userpanel::components.layouts.master')

@section('title', 'Callback Layout Example')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Callback Layout Example</h1>
            <p class="text-gray-600">Demonstrating the new callback-based layout syntax for cleaner, more intuitive form building.</p>
        </div>

        <!-- Navigation -->
        <div class="mb-6">
            <nav class="flex space-x-4">
                <a href="{{ route('userpanel.callback-layout') }}" 
                   class="px-4 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition-colors">
                    Basic Example
                </a>
                <a href="{{ route('userpanel.callback-layout.advanced') }}" 
                   class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors">
                    Advanced Example
                </a>
            </nav>
        </div>

        <!-- Info Alert -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Callback-Based Layout Syntax</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>This example shows the new callback-based syntax that makes form layout creation much cleaner and more intuitive. Instead of manually creating fields and then binding them to layout components, you can define everything inline within callbacks.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Code Example -->
        <div class="bg-gray-900 rounded-lg p-6 mb-6">
            <h3 class="text-white font-semibold mb-4">Example Code:</h3>
            <pre class="text-green-400 text-sm overflow-x-auto"><code>// Create layout and form services
$layout = new LayoutService();
$form = new FormService();
$layout->setFormService($form);

// The first column occupies 1/2 of the page width
$layout->column('1/2', function ($form, $column) {
    $column->addField(
        $form->text()
            ->name('title')
            ->label('Title')
            ->required()
    );
    
    $column->addField(
        $form->textarea()
            ->name('desc')
            ->label('Description')
            ->required()
    );
});

// The second column occupies 1/2 of the page width
$layout->column('1/2', function ($form, $column) {
    $column->addField(
        $form->number()
            ->name('view_count')
            ->label('View Count')
            ->value('0')
    );
    
    $column->addField(
        $form->radio()
            ->name('privilege')
            ->label('Privilege')
            ->options([
                1 => 'Public',
                2 => 'Private'
            ])
            ->required()
    );
});</code></pre>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Cleaner Syntax</h3>
                <p class="text-gray-600">Much more readable and intuitive than manual field binding. Everything is defined inline where it belongs.</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                <div class="text-green-500 mb-3">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Automatic Binding</h3>
                <p class="text-gray-600">Fields are automatically bound to their layout containers. No need to manually call addField() after creating fields.</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                <div class="text-purple-500 mb-3">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Flexible Widths</h3>
                <p class="text-gray-600">Support for fractional widths like '1/2', '1/3', '2/3' makes responsive layouts much easier to create.</p>
            </div>
        </div>
    </div>
</div>
@endsection 