@extends('userpanel::components.layouts.master')

@section('title', 'Products Management')
@section('page-title', 'Products Management')

@section('content')
    <!-- Breadcrumb -->
    <x-userpanel::breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Products']
    ]" />

    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Products Management</h1>
                <p class="mt-2 text-gray-600">Manage your product catalog, inventory, and pricing</p>
            </div>
            <a href="{{ route('products.create') }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fa fa-plus mr-2"></i>Create New Product
            </a>
        </div>
    </div>

    <!-- Data Table -->
    {!! $dataView->render() !!}
@endsection
