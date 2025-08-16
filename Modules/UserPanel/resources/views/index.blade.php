@extends('userpanel::components.layouts.master')

@section('title', $title ?? 'Resource Management')
@section('page-title', $title ?? 'Resource Management')

@section('content')
    <!-- Breadcrumb -->
    @if(isset($routePrefix))
        <x-userpanel::breadcrumb :items="[
            ['label' => 'Dashboard', 'url' => route('dashboard.index')],
            ['label' => $title ?? 'Resource Management']
        ]" />
    @endif

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $title ?? 'Resource Management' }}</h1>
        @if(isset($description))
            <p class="mt-2 text-gray-600">{{ $description }}</p>
        @endif
    </div>

    <!-- Content -->
    <div class="bg-white rounded-lg shadow-lg border border-gray-200">
        {!! $grid !!}
    </div>
@endsection
