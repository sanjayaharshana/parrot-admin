@extends('userpanel::components.layouts.master')

@section('title', 'Simple Layout Example')
@section('page-title', 'Simple Layout & Form Binding')

@section('content')
    <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
        <form method="{{ $form->getMethod() }}" action="{{ $form->getAction() }}" enctype="multipart/form-data" class="space-y-6">
            {!! $form->renderFormContent() !!}
            
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ url()->previous() }}" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Create
                </button>
            </div>
        </form>
    </div>

    <style>
        .tabs-container {
            @apply w-full;
        }
        
        .tab-button {
            @apply transition-all duration-200;
        }
        
        .tab-button:hover {
            @apply transform scale-105;
        }
        
        .tab-panel {
            @apply animate-fade-in;
        }
        
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>
@endsection
