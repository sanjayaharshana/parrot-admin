@extends('userpanel::components.layouts.master')

@section('title', $title ?? 'Resource Details')
@section('page-title', $title ?? 'Resource Details')

@section('content')
    <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
        <div class="space-y-6">
            @if(isset($model))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Resource Information</h3>
                        <dl class="space-y-3">
                            @if(isset($model->id))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">ID</dt>
                                    <dd class="text-sm text-gray-900">{{ $model->id }}</dd>
                                </div>
                            @endif
                            
                            @if(isset($model->title))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Title</dt>
                                    <dd class="text-sm text-gray-900">{{ $model->title }}</dd>
                                </div>
                            @endif
                            
                            @if(isset($model->desc))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                                    <dd class="text-sm text-gray-900">{{ $model->desc }}</dd>
                                </div>
                            @endif
                            
                            @if(isset($model->path))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Path</dt>
                                    <dd class="text-sm text-gray-900">{{ $model->path }}</dd>
                                </div>
                            @endif
                            
                            @if(isset($model->uploader_id))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Uploader ID</dt>
                                    <dd class="text-sm text-gray-900">{{ $model->uploader_id }}</dd>
                                </div>
                            @endif
                            
                            @if(isset($model->created_at))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Created At</dt>
                                    <dd class="text-sm text-gray-900">{{ $model->created_at->format('M d, Y H:i:s') }}</dd>
                                </div>
                            @endif
                            
                            @if(isset($model->updated_at))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Updated At</dt>
                                    <dd class="text-sm text-gray-900">{{ $model->updated_at->format('M d, Y H:i:s') }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500">No resource data available.</p>
                </div>
            @endif
            
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ url()->previous() }}" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Back
                </a>
                @if(isset($model))
                    <a href="{{ route('dashboard.edit', $model->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Edit
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection 