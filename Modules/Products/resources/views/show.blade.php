@extends('userpanel::components.layouts.master')

@section('title', $title ?? 'Product Details')
@section('page-title', $title ?? 'Product Details')

@section('content')
    <!-- Breadcrumb -->
    <x-userpanel::breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Products', 'url' => route('products.index')],
        ['label' => 'Product Details']
    ]" />

    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $title ?? 'Product Details' }}</h1>
                @if(isset($description))
                    <p class="mt-2 text-gray-600">{{ $description }}</p>
                @endif
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('products.edit', $product->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fa fa-edit mr-2"></i>Edit Product
                </a>
                <a href="{{ route('products.index') }}" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Product Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Product Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Essential Information -->
            <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                <div class="flex items-center mb-4">
                    <i class="fa fa-star text-yellow-500 mr-2"></i>
                    <h2 class="text-xl font-semibold text-gray-900">Essential Information</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                        <p class="text-gray-900 font-medium">{{ $product->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Type</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $product->product_type_label }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <p class="text-gray-900">{{ $product->category ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                        <p class="text-gray-900 font-mono">{{ $product->sku }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                        <p class="text-gray-900 font-mono">{{ $product->slug }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            @if($product->description || $product->brand || $product->vendor || $product->warranty)
            <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                <div class="flex items-center mb-4">
                    <i class="fa fa-info-circle text-blue-500 mr-2"></i>
                    <h2 class="text-xl font-semibold text-gray-900">Product Details</h2>
                </div>
                <div class="space-y-4">
                    @if($product->description)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <div class="text-gray-900 prose max-w-none">{!! $product->description !!}</div>
                    </div>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($product->brand)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                            <p class="text-gray-900">{{ $product->brand }}</p>
                        </div>
                        @endif
                        @if($product->vendor)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Vendor</label>
                            <p class="text-gray-900">{{ $product->vendor }}</p>
                        </div>
                        @endif
                        @if($product->warranty)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Warranty</label>
                            <p class="text-gray-900">{{ $product->warranty }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Pricing & Billing -->
            <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                <div class="flex items-center mb-4">
                    <i class="fa fa-dollar-sign text-green-500 mr-2"></i>
                    <h2 class="text-xl font-semibold text-gray-900">Pricing & Billing</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Base Price</label>
                        <p class="text-2xl font-bold text-green-600">${{ number_format($product->price, 2) }}</p>
                    </div>
                    @if($product->subscription_price)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subscription Price</label>
                        <p class="text-xl font-semibold text-blue-600">${{ number_format($product->subscription_price, 2) }}</p>
                    </div>
                    @endif
                    @if($product->shipping_cost)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Shipping Cost</label>
                        <p class="text-gray-900">${{ number_format($product->shipping_cost, 2) }}</p>
                    </div>
                    @endif
                    @if($product->tax_rate)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tax Rate</label>
                        <p class="text-gray-900">{{ $product->tax_rate }}%</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Inventory & Shipping -->
            @if($product->product_type === 'physical')
            <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                <div class="flex items-center mb-4">
                    <i class="fa fa-boxes text-orange-500 mr-2"></i>
                    <h2 class="text-xl font-semibold text-gray-900">Inventory & Shipping</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->stock_quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->stock_quantity }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stock Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst(str_replace('_', ' ', $product->stock_status)) }}
                        </span>
                    </div>
                    @if($product->weight)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Weight</label>
                        <p class="text-gray-900">{{ $product->weight }}g</p>
                    </div>
                    @endif
                    @if($product->dimensions)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dimensions</label>
                        <p class="text-gray-900">{{ $product->dimensions }}</p>
                    </div>
                    @endif
                    @if($product->barcode)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Barcode</label>
                        <p class="text-gray-900 font-mono">{{ $product->barcode }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Product Image -->
            @if($product->image)
            <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Image</h3>
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-64 object-cover rounded-lg">
            </div>
            @endif

            <!-- Product Settings -->
            <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Settings</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Featured Product</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_featured ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $product->is_featured ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Visible to Customers</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_visible ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->is_visible ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Featured on Homepage</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_featured_on_homepage ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $product->is_featured_on_homepage ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">New Product</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_new ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $product->is_new ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">On Sale</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_on_sale ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $product->is_on_sale ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Taxable</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_taxable ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $product->is_taxable ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    @if($product->product_type === 'physical')
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Requires Shipping</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->requires_shipping ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $product->requires_shipping ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    @endif
                    @if($product->product_type === 'digital')
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Downloadable</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_downloadable ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $product->is_downloadable ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- SEO Information -->
            @if($product->meta_title || $product->meta_description || $product->meta_keywords)
            <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">SEO Information</h3>
                <div class="space-y-3">
                    @if($product->meta_title)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                        <p class="text-sm text-gray-900">{{ $product->meta_title }}</p>
                    </div>
                    @endif
                    @if($product->meta_description)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                        <p class="text-sm text-gray-900">{{ $product->meta_description }}</p>
                    </div>
                    @endif
                    @if($product->meta_keywords)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
                        <p class="text-sm text-gray-900">{{ is_array($product->meta_keywords) ? implode(', ', $product->meta_keywords) : $product->meta_keywords }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
