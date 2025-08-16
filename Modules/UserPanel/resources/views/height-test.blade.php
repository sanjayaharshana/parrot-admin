@extends('userpanel::components.layouts.master')

@section('title', 'Height Test')
@section('page-title', 'CKEditor Height Test')

@section('content')
    <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
        <h3 class="text-lg font-semibold mb-4">Testing CKEditor Height Functionality</h3>
        
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
            <h4 class="font-semibold mb-2">Debug Information:</h4>
            <p class="text-sm text-gray-600">Check the browser console for height debugging information.</p>
            <p class="text-sm text-gray-600">Each CKEditor field should have a different height:</p>
            <ul class="text-sm text-gray-600 list-disc list-inside mt-2">
                <li>Content: 200px height</li>
                <li>Content 2: 400px height</li>
                <li>Description: 300px height</li>
            </ul>
        </div>
    </div>
@endsection
