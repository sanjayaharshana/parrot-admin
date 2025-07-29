@extends('userpanel::components.layouts.master')

@section('title', $title ?? 'Model Binding Example')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $title ?? 'Model Binding Example' }}</h1>
            <p class="text-gray-600">Demonstrating model binding and form submission with automatic data population and validation.</p>
        </div>

        <!-- Navigation -->
        <div class="mb-6">
            <nav class="flex space-x-4">
                <a href="{{ route('userpanel.model-binding') }}" 
                   class="px-4 py-2 rounded-lg {{ request()->routeIs('userpanel.model-binding') && !request()->has('advanced') ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }} hover:bg-blue-600 hover:text-white transition-colors">
                    Create User
                </a>
                <a href="{{ route('userpanel.model-binding.advanced') }}" 
                   class="px-4 py-2 rounded-lg {{ request()->routeIs('userpanel.model-binding.advanced') ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }} hover:bg-blue-600 hover:text-white transition-colors">
                    Advanced Form
                </a>
                @if(isset($user))
                <a href="{{ route('userpanel.model-binding.edit', $user->id) }}" 
                   class="px-4 py-2 rounded-lg {{ request()->routeIs('userpanel.model-binding.edit') ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700' }} hover:bg-green-600 hover:text-white transition-colors">
                    Edit User
                </a>
                @endif
            </nav>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">There were some errors with your submission:</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Info Alert -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Model Binding Features</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>This form demonstrates automatic model binding, data population, validation, and form submission handling. Fields are automatically populated with model data when editing.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Code Example -->
        <div class="bg-gray-900 rounded-lg p-6 mb-6">
            <h3 class="text-white font-semibold mb-4">Example Code:</h3>
            <pre class="text-green-400 text-sm overflow-x-auto"><code>// Create form with model binding
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
}</code></pre>
        </div>

        <!-- Rendered Form -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Form with Model Binding</h2>
                @if(isset($user))
                <p class="text-sm text-gray-600 mt-1">Editing user: {{ $user->name }} ({{ $user->email }})</p>
                @endif
            </div>
            <div class="p-6">
                {!! $form !!}
            </div>
        </div>

        <!-- Features -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                <div class="text-blue-500 mb-3">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Auto Population</h3>
                <p class="text-gray-600">Fields automatically populate with model data when editing existing records.</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                <div class="text-green-500 mb-3">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Validation</h3>
                <p class="text-gray-600">Built-in validation with custom error messages and automatic error display.</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                <div class="text-purple-500 mb-3">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Form Handling</h3>
                <p class="text-gray-600">Automatic form submission handling with CSRF protection and method spoofing.</p>
            </div>
        </div>

        <!-- Benefits -->
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Benefits of Model Binding</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Development Speed</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• No manual field population</li>
                        <li>• Automatic form generation</li>
                        <li>• Built-in validation handling</li>
                        <li>• CSRF protection included</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Data Integrity</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Automatic data validation</li>
                        <li>• Model relationship support</li>
                        <li>• Type-safe data handling</li>
                        <li>• Error handling built-in</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">User Experience</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Pre-filled forms for editing</li>
                        <li>• Clear error messages</li>
                        <li>• Success feedback</li>
                        <li>• Form state preservation</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Maintainability</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Centralized form logic</li>
                        <li>• Reusable form components</li>
                        <li>• Easy to modify and extend</li>
                        <li>• Consistent form behavior</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 