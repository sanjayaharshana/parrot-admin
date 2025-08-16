@extends('userpanel::components.layouts.master')

@section('title', 'Switch Field Test')
@section('page-title', 'Switch Field Test')

@section('content')
    <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
        <h3 class="text-lg font-semibold mb-4">Testing Switch Field Functionality</h3>
        
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
                <li><strong>Active Status:</strong> Switch with searchable and sortable</li>
                <li><strong>Featured Item:</strong> Switch with default value (true)</li>
                <li><strong>Public Visibility:</strong> Switch with searchable only</li>
                <li><strong>Verification Status:</strong> Switch with required validation</li>
            </ul>
            
            <h4 class="font-semibold mb-2 mt-4">Available Methods:</h4>
            <ul class="text-sm text-gray-600 list-disc list-inside space-y-1">
                <li><code>->switch('field_name')</code> - Create a switch/toggle field</li>
                <li><code>->label('Custom Label')</code> - Set a custom label for the field</li>
                <li><code>->searchable()</code> - Make the field searchable in data views</li>
                <li><code>->sortable()</code> - Make the field sortable in data views</li>
                <li><code>->required()</code> - Make the field required</li>
                <li><code>->value(true/false)</code> - Set default value</li>
            </ul>
            
            <h4 class="font-semibold mb-2 mt-4">Usage in ResourceService:</h4>
            <div class="bg-gray-100 p-3 rounded text-sm font-mono">
                ->switch('is_active')->searchable()->sortable()
            </div>
        </div>
    </div>
@endsection
