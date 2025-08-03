@extends('userpanel::components.layouts.master')

@section('title', 'Simple Layout Example')
@section('page-title', 'Simple Layout & Form Binding')

@section('content')

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">

            {!! $grid !!}
        </div>
    </div>
@endsection
