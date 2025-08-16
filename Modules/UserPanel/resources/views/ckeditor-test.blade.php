@extends('userpanel::components.layouts.master')

@section('title', 'CKEditor Test')
@section('page-title', 'CKEditor ResourceService Test')

@section('content')
    <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">CKEditor ResourceService Integration Test</h2>
            <p class="text-gray-600">
                This test demonstrates that CKEditor is now working correctly with ResourceService. 
                Both the <code>ckeditor()</code> method and <code>richText()</code> method should work.
            </p>
        </div>

        <form method="POST" action="{{ route('ckeditor-test.store') }}" class="space-y-6">
            @csrf
            {!! $form->renderFormContent() !!}
            
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ url()->previous() }}" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Submit Test
                </button>
            </div>
        </form>
    </div>

    <div class="mt-8 bg-white rounded-lg shadow-lg p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">What This Test Shows</h3>
        
        <div class="space-y-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-medium text-gray-800 mb-2">1. CKEditor with textarea() method</h4>
                <pre class="text-sm text-gray-700 bg-white p-3 rounded border"><code>$resource->tab('general', 'General Information')
    ->textarea('description')->ckeditor(true)
    ->end();</code></pre>
                <p class="text-sm text-gray-600 mt-2">This should show a textarea with CKEditor enabled.</p>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-medium text-gray-800 mb-2">2. CKEditor with richText() method</h4>
                <pre class="text-sm text-gray-700 bg-white p-3 rounded border"><code>$resource->tab('content', 'Rich Content')
    ->richText('content')
    ->end();</code></pre>
                <p class="text-sm text-gray-600 mt-2">This should show a rich text field with CKEditor automatically enabled.</p>
            </div>
        </div>
    </div>

    <div class="mt-8 bg-white rounded-lg shadow-lg p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Expected Results</h3>
        <ul class="space-y-2 text-gray-700">
            <li class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                Both textarea fields should display as rich text editors
            </li>
            <li class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                Form submission should work correctly
            </li>
            <li class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                Content should be properly synchronized between editor and form
            </li>
        </ul>
    </div>
@endsection
