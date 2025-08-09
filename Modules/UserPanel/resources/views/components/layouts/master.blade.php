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
    </style>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="gradient-bg text-white shadow-lg w-64 flex-shrink-0">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-white">{{ config('app.name', 'Laravel') }}</h1>
            </div>

            <nav class="mt-6">
                <div class="px-4 space-y-2">
                   @foreach(\Modules\UserPanel\Services\UserPanelService::getNavMenuItem() as $menuItem)
                        <a href="{{ url($menuItem['uri']) }}"
                           class="flex items-center px-4 py-2 text-white rounded-lg hover:bg-white hover:bg-opacity-20 transition-all duration-200 {{ request()->routeIs($menuItem['name']) ? 'bg-white bg-opacity-20 text-white' : '' }}">
                            <i class="{{ $menuItem['icon'] }} w-5 h-5 mr-3"></i>
                            {{ $menuItem['name'] ? ucfirst(str_replace(['userpanel.', 'pluginmanager.', '-'], ['', '', ' '], $menuItem['name'])) : 'Dashboard' }}
                        </a>
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

    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs@3.13.3/dist/cdn.min.js" defer></script>
    <script>
      window.formDataGrid = window.formDataGrid || function(name, columns, endpoint){
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
          currency: function(v){ try { return new Intl.NumberFormat(undefined,{style:"currency",currency:"USD"}).format(Number(v||0)); } catch(e){ return Number(v||0).toFixed(2); } },
          recalc: function(){ this.grandTotal = this.rows.reduce(function(s,r){ return s + ((Number(r.quantity)||0) * (Number(r.price)||0)); }, 0); this.serialized = JSON.stringify(this.rows); },
          remove: function(idx){ this.rows.splice(idx,1); this.recalc(); },
          openPicker: function(){ this.open = true; this.loadResults(); },
          loadResults: async function(){ if(!this.endpoint) return; const url = this.endpoint + (this.search ? (this.endpoint.indexOf('?')>=0?'&':'?') + 'q=' + encodeURIComponent(this.search) : ''); const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } }); this.results = res.ok ? await res.json() : []; },
          add: function(item){ const row = { __id: Math.random().toString(36).slice(2), id: item.id, item_name: item.name || item.item_name, quantity: 1, price: Number(item.price)||0, purchase_date: (new Date()).toISOString().slice(0,10) }; this.rows.push(row); this.recalc(); }
        };
      };
    </script>
</body>
</html>
