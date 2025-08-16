@extends('userpanel::components.layouts.master')

@section('title', 'CKEditor Demo')
@section('page-title', 'CKEditor Integration Demo')

@section('content')
    <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">CKEditor Integration Examples</h2>
            <p class="text-gray-600">
                This demo shows how to use CKEditor with your form system. You can use either the <code>richText()</code> method 
                or manually enable CKEditor on existing textarea fields using the <code>ckeditor()</code> method.
            </p>
        </div>

        <form method="{{ $form->getMethod() }}" action="{{ $form->getAction() }}" class="space-y-6">
            {!! $form->renderFormContent() !!}
            
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ url()->previous() }}" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Save Content
                </button>
            </div>
        </form>
    </div>

    <div class="mt-8 bg-white rounded-lg shadow-lg p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Usage Examples</h3>
        
        <div class="space-y-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-medium text-gray-800 mb-2">1. Using richText() method (Recommended)</h4>
                <pre class="text-sm text-gray-700 bg-white p-3 rounded border"><code>$this->form->richText()
    ->name('content')
    ->label('Content')
    ->required();</code></pre>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-medium text-gray-800 mb-2">2. Manually enabling CKEditor on textarea</h4>
                <pre class="text-sm text-gray-700 bg-white p-3 rounded border"><code>$this->form->textarea()
    ->name('content')
    ->label('Content')
    ->ckeditor(true);</code></pre>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-medium text-gray-800 mb-2">3. In ResourceService</h4>
                <pre class="text-sm text-gray-700 bg-white p-3 rounded border"><code>$this->richText('content', [
    'label' => 'Content',
    'required' => true
]);</code></pre>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-medium text-gray-800 mb-2">4. In TabBuilder</h4>
                <pre class="text-sm text-gray-700 bg-white p-3 rounded border"><code>$tab->richText('content', [
    'label' => 'Content',
    'required' => true
]);</code></pre>
            </div>
        </div>
    </div>

    <div class="mt-8 bg-white rounded-lg shadow-lg p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Features</h3>
        <ul class="space-y-2 text-gray-700">
            <li class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                Rich text formatting (bold, italic, headings, lists)
            </li>
            <li class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                Link insertion and management
            </li>
            <li class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                Table creation and editing
            </li>
            <li class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                Image upload and embedding
            </li>
            <li class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                Blockquotes and code blocks
            </li>
            <li class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                Responsive design with mobile support
            </li>
        </ul>
    </div>
@endsection
