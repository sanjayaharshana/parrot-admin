@extends('userpanel::components.layouts.master')

@section('title', 'Grid Examples with Search & Filters')
@section('page-title', 'Enhanced Grid Examples')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        
        <!-- Introduction -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Enhanced Grid Examples</h2>
            <p class="text-gray-600 mb-6">
                This page demonstrates the enhanced search and filter functionality in the UserPanel Module grid system.
                Each example shows different types of search and filter options.
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Simple Example -->
                <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Simple Layout</h3>
                    <p class="text-blue-700 mb-4">Basic grid with search and date range filters.</p>
                    <a href="{{ route('userpanel.simple-layout') }}" 
                       class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                        View Example
                    </a>
                </div>
                
                <!-- Users with Search -->
                <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                    <h3 class="text-lg font-semibold text-green-900 mb-2">Users with Search</h3>
                    <p class="text-green-700 mb-4">Enhanced search across multiple columns with various filters.</p>
                    <a href="{{ route('userpanel.test.users-with-search') }}" 
                       class="inline-block bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
                        View Example
                    </a>
                </div>
                
                <!-- Products with Filters -->
                <div class="bg-purple-50 rounded-lg p-6 border border-purple-200">
                    <h3 class="text-lg font-semibold text-purple-900 mb-2">Products with Filters</h3>
                    <p class="text-purple-700 mb-4">Multiple filter types including select dropdowns and date ranges.</p>
                    <a href="{{ route('userpanel.test.products-with-filters') }}" 
                       class="inline-block bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition-colors">
                        View Example
                    </a>
                </div>
                
                <!-- Advanced Example -->
                <div class="bg-orange-50 rounded-lg p-6 border border-orange-200">
                    <h3 class="text-lg font-semibold text-orange-900 mb-2">Advanced Example</h3>
                    <p class="text-orange-700 mb-4">Complete example with all features including actions and bulk operations.</p>
                    <a href="{{ route('userpanel.test.advanced-example') }}" 
                       class="inline-block bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors">
                        View Example
                    </a>
                </div>
                
                <!-- Data View Advanced -->
                <div class="bg-red-50 rounded-lg p-6 border border-red-200">
                    <h3 class="text-lg font-semibold text-red-900 mb-2">Data View Advanced</h3>
                    <p class="text-red-700 mb-4">Advanced data view with comprehensive search and filter options.</p>
                    <a href="{{ route('userpanel.data-view.advanced') }}" 
                       class="inline-block bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors">
                        View Example
                    </a>
                </div>
                
                <!-- Basic Data View -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Basic Data View</h3>
                    <p class="text-gray-700 mb-4">Simple data view with basic functionality.</p>
                    <a href="{{ route('userpanel.data-view') }}" 
                       class="inline-block bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                        View Example
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Features Overview -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Features Overview</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Search Features -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Search Features</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Global search across multiple columns
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Individual column search
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Case-insensitive search
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Search with clear button
                        </li>
                    </ul>
                </div>
                
                <!-- Filter Features -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Filter Features</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Text filters for any field
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Select dropdown filters
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Date range filters
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Multiple filter combinations
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection 