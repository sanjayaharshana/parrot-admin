@extends('userpanel::components.layouts.master')

@section('title', 'Select Field Test')
@section('page-title', 'Select Field Test')

@section('content')
    <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
        <h3 class="text-lg font-semibold mb-4">Testing Select Field Functionality</h3>
        
        <form method="{{ $form->getMethod() }}" action="{{ $form->getAction() }}" enctype="multipart/form-data" class="space-y-6">
            {!! $form->renderFormContent() !!}
            
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ url()->previous() }}" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Submit
                </button>
            </div>
        </form>
        
        <div class="mt-8 p-4 bg-gray-50 rounded-lg">
            <h4 class="font-semibold mb-2">Test Cases:</h4>
            <ul class="text-sm text-gray-600 list-disc list-inside space-y-1">
                <li><strong>Category:</strong> Select with options array, searchable, custom label "Category"</li>
                <li><strong>User:</strong> Select with array of objects, searchable, custom label "User"</li>
                <li><strong>Status:</strong> Select with simple key-value array, custom label "Status"</li>
            </ul>
            
            <h4 class="font-semibold mb-2 mt-4">Available Methods:</h4>
            <ul class="text-sm text-gray-600 list-disc list-inside space-y-1">
                <li><code>->options($array)</code> - Set the options for the select field</li>
                <li><code>->searchable()</code> - Make the select field searchable</li>
                <li><code>->label('Custom Label')</code> - Set a custom label for the field</li>
                <li><code>->required()</code> - Make the field required</li>
            </ul>
        </div>
    </div>
@endsection
