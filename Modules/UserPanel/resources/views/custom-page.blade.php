@extends('userpanel::components.layouts.master')

@section('title', $title ?? 'Resource Details')
@section('page-title', $title ?? 'Resource Details')

@section('content')
    {!! $layout ?? '' !!}
@endsection
