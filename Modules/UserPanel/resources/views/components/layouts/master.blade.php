<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Laravel') }}</title>

    <meta name="description" content="{{ $description ?? '' }}">
    <meta name="keywords" content="{{ $keywords ?? '' }}">
    <meta name="author" content="{{ $author ?? '' }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        [x-cloak] { display: none !important; }

        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hover-gradient:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
        }

        /* Switch/Toggle Styles */
        .toggle-checkbox:checked {
            transform: translateX(24px) !important;
        }
        
        .toggle-checkbox:checked + .toggle-label {
            background-color: #3b82f6;
        }
        
        .toggle-label {
            background-color: #d1d5db;
        }

        /* Preloader Styles - Inline Fallback */
        .preloader {
            position: fixed;
            top: 0;
            left: 16rem;
            width: calc(100% - 16rem);
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 1;
            visibility: visible;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            backdrop-filter: blur(2px);
        }

        .preloader.hidden {
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
        }

        .preloader-content {
            text-align: center;
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .preloader-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid #e5e7eb;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1.5rem;
        }

        .preloader-text {
            color: #667eea;
            font-size: 1rem;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
            margin: 0;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .preloader {
                left: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Preloader -->
    <div id="preloader" class="preloader">
        <div class="preloader-content">
            <div class="preloader-spinner"></div>
            <div class="preloader-text">Loading...</div>
        </div>
    </div>

    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="gradient-bg text-white shadow-lg w-64 flex-shrink-0">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-white">{{ config('app.name', 'Laravel') }}</h1>
            </div>

            <nav class="mt-6">
                <div class="px-4 space-y-2">
                    @php
                        $transformName = function ($routeName) {
                            if (!$routeName) return '';
                            return ucfirst(str_replace(['userpanel.', 'pluginmanager.', '-'], ['', '', ' '], $routeName));
                        };
                    @endphp
                    @foreach(\Modules\UserPanel\Services\UserPanelService::getNavMenuItem() as $menuItem)
                        @if(isset($menuItem['children']))
                            @php
                                $isActiveGroup = collect($menuItem['children'] ?? [])->contains(function($child){
                                    return request()->routeIs($child['name'] ?? '');
                                });
                            @endphp
                            <div x-data="{ open: {{ $isActiveGroup ? 'true' : 'false' }} }" class="space-y-1">
                                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2 text-white rounded-lg hover:bg-white hover:bg-opacity-20 transition-all duration-200">
                                    <span class="flex items-center">
                                        <i class="{{ $menuItem['icon'] ?? 'fa fa-folder' }} w-5 h-5 mr-3"></i>
                                        {{ $menuItem['label'] }}
                                    </span>
                                    <i class="fas fa-chevron-down transform transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="open" x-transition class="ml-3 pl-1 border-l border-white border-opacity-20 space-y-1">
                                    @foreach($menuItem['children'] as $child)
                                        <a href="{{ url($child['uri']) }}"
                                           class="flex items-center px-4 py-2 text-white text-sm rounded-lg hover:bg-white hover:bg-opacity-20 transition-all duration-200 {{ request()->routeIs($child['name']) ? 'bg-white bg-opacity-20 text-white' : '' }}">
                                            <i class="{{ $child['icon'] }} w-4 h-4 mr-3"></i>
                                            {{ $transformName($child['name']) }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ url($menuItem['uri']) }}"
                               class="flex items-center px-4 py-2 text-white rounded-lg hover:bg-white hover:bg-opacity-20 transition-all duration-200 {{ request()->routeIs($menuItem['name']) ? 'bg-white bg-opacity-20 text-white' : '' }}">
                                <i class="{{ $menuItem['icon'] }} w-5 h-5 mr-3"></i>
                                {{ $transformName($menuItem['name']) ?: 'Dashboard' }}
                            </a>
                        @endif
                    @endforeach

                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fas fa-bell w-5 h-5"></i>
                        </button>

                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 transition-colors">
                                <div class="w-8 h-8 gradient-bg rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <span class="hidden md:block">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6 bg-gray-50">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
                @include('userpanel::components.media-manager')
            </main>
        </div>
    </div>


    
    <!-- CKEditor Assets -->
    @vite(['resources/css/ckeditor-only.css', 'resources/js/ckeditor-only.js'])
    
    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs@3.13.3/dist/cdn.min.js" defer></script>
    
    <!-- Preloader Script - Inline Fallback -->
    <script>
        // Preloader functionality
        function initPreloader() {
            const preloader = document.getElementById('preloader');
            
            if (preloader) {
                console.log('Preloader found, initializing...');
                
                // Hide preloader after page loads
                setTimeout(function() {
                    console.log('Hiding preloader...');
                    preloader.classList.add('hidden');
                    // Remove from DOM after animation
                    setTimeout(function() {
                        if (preloader && preloader.parentNode) {
                            console.log('Removing preloader from DOM...');
                            preloader.parentNode.removeChild(preloader);
                        }
                    }, 300);
                }, 1000); // Increased delay to 1 second

                // Show preloader on navigation
                window.addEventListener('beforeunload', function() {
                    preloader.classList.remove('hidden');
                });

                // Show preloader on AJAX requests
                document.addEventListener('ajax:start', function() {
                    preloader.classList.remove('hidden');
                });

                document.addEventListener('ajax:end', function() {
                    setTimeout(function() {
                        preloader.classList.add('hidden');
                    }, 300);
                });

                // Intercept fetch requests to show preloader
                const originalFetch = window.fetch;
                let activeRequests = 0;

                window.fetch = function(...args) {
                    activeRequests++;
                    preloader.classList.remove('hidden');

                    return originalFetch(...args)
                        .finally(function() {
                            activeRequests--;
                            if (activeRequests === 0) {
                                setTimeout(function() {
                                    preloader.classList.add('hidden');
                                }, 300);
                            }
                        });
                };
            } else {
                console.log('Preloader not found, retrying...');
                // Retry after a short delay
                setTimeout(initPreloader, 100);
            }
        }

        // Initialize preloader when DOM is ready
        console.log('Preloader script loaded, document readyState:', document.readyState);
        
        if (document.readyState === 'loading') {
            console.log('DOM still loading, waiting for DOMContentLoaded...');
            document.addEventListener('DOMContentLoaded', initPreloader);
        } else {
            console.log('DOM already ready, initializing preloader immediately...');
            // DOM is already ready
            initPreloader();
        }

        // Backup: Also hide preloader when window fully loads
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                console.log('Window loaded, hiding preloader...');
                setTimeout(function() {
                    preloader.classList.add('hidden');
                    setTimeout(function() {
                        if (preloader && preloader.parentNode) {
                            preloader.parentNode.removeChild(preloader);
                        }
                    }, 300);
                }, 500);
            }
        });

        // Global functions for manual control
        window.showUserPanelPreloader = function() {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                preloader.classList.remove('hidden');
            }
        };

        window.hideUserPanelPreloader = function() {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                setTimeout(function() {
                    preloader.classList.add('hidden');
                }, 300);
            }
        };

        // Force hide preloader (emergency function)
        window.forceHidePreloader = function() {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                console.log('Force hiding preloader...');
                preloader.classList.add('hidden');
                preloader.style.display = 'none';
                preloader.style.opacity = '0';
                preloader.style.visibility = 'hidden';
                preloader.style.pointerEvents = 'none';
                
                setTimeout(function() {
                    if (preloader && preloader.parentNode) {
                        console.log('Removing preloader from DOM...');
                        preloader.parentNode.removeChild(preloader);
                    }
                }, 100);
            }
        };

        // Auto-hide preloader after 3 seconds (safety measure)
        setTimeout(function() {
            console.log('Safety timeout reached, force hiding preloader...');
            window.forceHidePreloader();
        }, 3000);

        // Additional safety: hide preloader when page becomes visible
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                setTimeout(function() {
                    window.forceHidePreloader();
                }, 1000);
            }
        });

        window.formDataGrid = window.formDataGrid || function(name, columns, endpoint) {
            return {
                name: name,
                columns: columns,
                endpoint: endpoint,
                rows: [],
                open: false,
                search: "",
                results: [],
                grandTotal: 0,
                serialized: "[]",
                currency: function(v) { 
                    try { 
                        return new Intl.NumberFormat(undefined, {style: "currency", currency: "USD"}).format(Number(v || 0)); 
                    } catch(e) { 
                        return Number(v || 0).toFixed(2); 
                    } 
                },
                recalc: function() { 
                    this.grandTotal = this.rows.reduce(function(s, r) { 
                        return s + ((Number(r.quantity) || 0) * (Number(r.price) || 0)); 
                    }, 0); 
                    this.serialized = JSON.stringify(this.rows); 
                },
                remove: function(idx) { 
                    this.rows.splice(idx, 1); 
                    this.recalc(); 
                },
                openPicker: function() { 
                    this.open = true; 
                    this.loadResults(); 
                },
                loadResults: async function() { 
                    if (!this.endpoint) return; 
                    const url = this.endpoint + (this.endpoint.indexOf('?') >= 0 ? '&' : '?') + 'q=' + encodeURIComponent(this.search); 
                    const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } }); 
                    this.recalc(); 
                },
                add: function(item) { 
                    const row = { 
                        __id: Math.random().toString(36).slice(2), 
                        id: item.id, 
                        item_name: item.name || item.item_name, 
                        quantity: 1, 
                        price: Number(item.price) || 0, 
                        purchase_date: (new Date()).toISOString().slice(0, 10) 
                    }; 
                    this.rows.push(row); 
                    this.recalc(); 
                }
            };
        };
    </script>
</body>
</html>
