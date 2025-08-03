@extends('userpanel::components.layouts.master')

@section('title', 'Edit Resource')
@section('page-title', 'Edit Resource')

@section('content')
    <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            {!! $layout !!}
            
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ url()->previous() }}" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Update
                </button>
            </div>
        </form>
    </div>
@endsection 