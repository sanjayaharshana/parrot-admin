@extends('userpanel::components.layouts.master')

@section('title', 'Simple Layout Example')
@section('page-title', 'Simple Layout & Form Binding')

@section('content')

    <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">

        <form method="POST" class="space-y-6">
            {!! $grid !!}
        </form>
    </div>
@endsection
