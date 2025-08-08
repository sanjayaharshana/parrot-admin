<x-documentation::layouts.master>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border p-6 sticky top-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-folder-open mr-2"></i>
                    Categories
                </h2>
                <nav class="space-y-2">
                    @foreach($categories as $category)
                        <a href="{{ route('documentation.category', $category->slug) }}" 
                           class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-gray-900 transition-colors">
                            <i class="{{ $category->icon }} mr-3" style="color: {{ $category->color }}"></i>
                            {{ $category->name }}
                            <span class="ml-auto text-xs text-gray-500">{{ $category->activePages->count() }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            <!-- Hero Section -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-sm text-white p-8 mb-8">
                <div class="max-w-3xl">
                    <h1 class="text-4xl font-bold mb-4">
                        Welcome to Documentation
                    </h1>
                    <p class="text-xl text-blue-100 mb-6">
                        Comprehensive guides and tutorials for building amazing applications with our framework.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('documentation.category', 'getting-started') }}" 
                           class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                            Get Started
                        </a>
                        <a href="{{ route('documentation.category', 'grid-system') }}" 
                           class="border border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors">
                            Grid System
                        </a>
                    </div>
                </div>
            </div>

            <!-- Featured Pages -->
            @if($featuredPages->count() > 0)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">
                        <i class="fas fa-star mr-2 text-yellow-500"></i>
                        Featured Documentation
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($featuredPages as $page)
                            <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                                <div class="p-6">
                                    <div class="flex items-center mb-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                              style="background-color: {{ $page->category->color }}20; color: {{ $page->category->color }};">
                                            {{ $page->category->name }}
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                        <a href="{{ route('documentation.page', [$page->category->slug, $page->slug]) }}" 
                                           class="hover:text-blue-600 transition-colors">
                                            {{ $page->title }}
                                        </a>
                                    </h3>
                                    @if($page->excerpt)
                                        <p class="text-gray-600 text-sm mb-4">
                                            {{ Str::limit($page->excerpt, 120) }}
                                        </p>
                                    @endif
                                    <div class="flex items-center text-xs text-gray-500">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $page->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Categories Grid -->
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-th-large mr-2"></i>
                    Browse by Category
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($categories as $category)
                        <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex items-center mb-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 rounded-lg flex items-center justify-center" 
                                             style="background-color: {{ $category->color }}20;">
                                            <i class="{{ $category->icon }} text-2xl" style="color: {{ $category->color }}"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            <a href="{{ route('documentation.category', $category->slug) }}" 
                                               class="hover:text-blue-600 transition-colors">
                                                {{ $category->name }}
                                            </a>
                                        </h3>
                                        @if($category->description)
                                            <p class="text-sm text-gray-600">{{ $category->description }}</p>
                                        @endif
                                    </div>
                                </div>
                                @if($category->activePages->count() > 0)
                                    <div class="border-t pt-4">
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Recent Pages</h4>
                                        <ul class="space-y-1">
                                            @foreach($category->activePages->take(3) as $page)
                                                <li>
                                                    <a href="{{ route('documentation.page', [$category->slug, $page->slug]) }}" 
                                                       class="text-sm text-gray-600 hover:text-blue-600 transition-colors">
                                                        {{ $page->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        @if($category->activePages->count() > 3)
                                            <a href="{{ route('documentation.category', $category->slug) }}" 
                                               class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                                View all {{ $category->activePages->count() }} pages →
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-documentation::layouts.master>
