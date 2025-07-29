@extends('userpanel::components.layouts.master')

@section('title', 'Form Demo')
@section('page-title', 'Enhanced Form Demo')

@section('content')
<div class="space-y-6">
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    This is a demonstration of the enhanced FormService with modern styling, labels, placeholders, and various field types.
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-2xl">
        {!! $form !!}
    </div>
</div>
@endsection
