<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ $title ?? 'Parrot Admin - Modern SaaS Solution' }}</title>

    <meta name="description" content="{{ $description ?? 'Transform your business with our modern SaaS solution. Built for growth, designed for success.' }}">
    <meta name="keywords" content="{{ $keywords ?? 'SaaS, Admin Panel, Business Management, Dashboard' }}">
    <meta name="author" content="{{ $author ?? 'Parrot Admin' }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    {{-- Vite CSS --}}
    {{-- {{ module_vite('build-landing', 'resources/assets/sass/app.scss') }} --}}

    @stack('styles')
</head>
<body>
    @include('landing::components.partials.header')
    
    <main>
        @yield('content')
    </main>
    
    @include('landing::components.partials.footer')
    
    @include('landing::components.partials.common-styles')
    
    @stack('scripts')
</body>
</html> 