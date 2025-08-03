@extends('userpanel::components.layouts.master')

@section('title', 'View Resource')
@section('page-title', 'View Resource')

@section('content')
    <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if(isset($product))
                <div>
                    <h3 class="text-lg font-semibold mb-4">Product Details</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Name:</label>
                            <p class="text-gray-900">{{ $product->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">SKU:</label>
                            <p class="text-gray-900">{{ $product->sku }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Price:</label>
                            <p class="text-gray-900">${{ number_format($product->price, 2) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Stock:</label>
                            <p class="text-gray-900">{{ $product->stock }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Category:</label>
                            <p class="text-gray-900">{{ ucfirst($product->category) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Status:</label>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $product->is_active ? 'text-green-800 bg-green-100' : 'text-red-800 bg-red-100' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Description</h3>
                    <p class="text-gray-700">{{ $product->description }}</p>
                    
                    @if($product->image)
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold mb-4">Product Image</h3>
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full max-w-md rounded-lg shadow-md">
                        </div>
                    @endif
                </div>
            @else
                <div class="col-span-2">
                    <p class="text-gray-600">No data available to display.</p>
                </div>
            @endif
        </div>
        
        <div class="flex justify-end space-x-4 mt-6">
            <a href="{{ url()->previous() }}" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                Back
            </a>
            @if(isset($product))
                <a href="{{ route('products.edit', $product->id) }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Edit
                </a>
            @endif
        </div>
    </div>
@endsection 