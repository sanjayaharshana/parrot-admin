<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>@yield('title', 'Documentation') - {{ config('app.name', 'Laravel') }}</title>

    <meta name="description" content="@yield('description', '')">
    <meta name="keywords" content="@yield('keywords', '')">
    <meta name="author" content="@yield('author', '')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .prose {
            max-width: none;
        }
        .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
            color: #1f2937;
            font-weight: 600;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        .prose h1 {
            font-size: 2.25rem;
            line-height: 2.5rem;
        }
        .prose h2 {
            font-size: 1.875rem;
            line-height: 2.25rem;
        }
        .prose h3 {
            font-size: 1.5rem;
            line-height: 2rem;
        }
        .prose p {
            margin-bottom: 1rem;
            line-height: 1.75;
        }
        .prose ul, .prose ol {
            margin-bottom: 1rem;
            padding-left: 1.5rem;
        }
        .prose li {
            margin-bottom: 0.5rem;
        }
        .prose code {
            background-color: #f3f4f6;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }
        .prose pre {
            background-color: #1f2937;
            color: #f9fafb;
            padding: 1rem;
            border-radius: 0.5rem;
            overflow-x: auto;
            margin-bottom: 1rem;
        }
        .prose pre code {
            background-color: transparent;
            padding: 0;
            color: inherit;
        }
        .prose table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        .prose th, .prose td {
            border: 1px solid #d1d5db;
            padding: 0.75rem;
            text-align: left;
        }
        .prose th {
            background-color: #f9fafb;
            font-weight: 600;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('documentation.index') }}" class="text-xl font-bold text-gray-900">
                            <i class="fas fa-book-open mr-2"></i>
                            Documentation
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="{{ route('documentation.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>
                        Add Page
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        {{ $slot }}
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center text-gray-500">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
