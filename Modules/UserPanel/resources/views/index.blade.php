@extends('userpanel::components.layouts.master')

@section('title', 'Simple Layout Example')
@section('page-title', 'Simple Layout & Form Binding')

@section('content')

    <div class="container mx-auto px-4 py-8">


        <div class="max-w-7xl mx-auto">

            <!-- Rendered Grid -->
            <div class="bg-white rounded-lg shadow-lg border border-gray-200">


                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Data Grid</h2>
                    <p class="text-sm text-gray-600 mt-1">Showing users with sortable columns and custom display logic</p>
                </div>
                <div class="p-6">
                    {!! $grid !!}

                </div>
            </div>
        </div>

    </div>
@endsection
