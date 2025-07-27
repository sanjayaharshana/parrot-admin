@extends('userpanel::components.layouts.master')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold gradient-text">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-gray-600 mt-1">Here's what's happening with your account today.</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Last login</p>
                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->updated_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-project-diagram text-purple-600 w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Projects</p>
                    <p class="text-2xl font-bold text-gray-900">12</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Tasks</p>
                    <p class="text-2xl font-bold text-gray-900">8</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-star text-purple-600 w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Rating</p>
                    <p class="text-2xl font-bold text-gray-900">4.8</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('userpanel.settings') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-200 transition-colors">
                <i class="fas fa-cog text-purple-500 w-5 h-5 mr-3"></i>
                <span class="font-medium text-gray-700">Settings</span>
            </a>
            <a href="{{ route('userpanel.subscription') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-200 transition-colors">
                <i class="fas fa-credit-card text-purple-500 w-5 h-5 mr-3"></i>
                <span class="font-medium text-gray-700">Subscription</span>
            </a>
            <a href="#" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-yellow-50 hover:border-yellow-200 transition-colors">
                <i class="fas fa-plus-circle text-yellow-500 w-5 h-5 mr-3"></i>
                <span class="font-medium text-gray-700">New Project</span>
            </a>
            <a href="#" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-200 transition-colors">
                <i class="fas fa-upload text-green-500 w-5 h-5 mr-3"></i>
                <span class="font-medium text-gray-700">Upload Files</span>
            </a>
        </div>
    </div>
</div>
@endsection
