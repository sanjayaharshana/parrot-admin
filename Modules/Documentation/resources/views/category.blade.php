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
                    @foreach($categories ?? [] as $cat)
                        <a href="{{ route('documentation.category', $cat->slug) }}" 
                           class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ $cat->id === $category->id ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="{{ $cat->icon }} mr-3" style="color: {{ $cat->color }}"></i>
                            {{ $cat->name }}
                            <span class="ml-auto text-xs text-gray-500">{{ $cat->activePages->count() }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            <!-- Category Header -->
            <div class="bg-white rounded-lg shadow-sm border p-8 mb-8">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 rounded-lg flex items-center justify-center" 
                             style="background-color: {{ $category->color }}20;">
                            <i class="{{ $category->icon }} text-3xl" style="color: {{ $category->color }}"></i>
                        </div>
                    </div>
                    <div class="ml-6">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
                        @if($category->description)
                            <p class="text-lg text-gray-600 mt-2">{{ $category->description }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Pages Grid -->
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-file-alt mr-2"></i>
                        Documentation Pages
                    </h2>
                    <span class="text-sm text-gray-500">{{ $pages->total() }} pages</span>
                </div>

                @if($pages->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($pages as $page)
                            <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                                <div class="p-6">
                                    <div class="flex items-start justify-between mb-2">
                                        @if($page->is_featured)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-star mr-1"></i>
                                                Featured
                                            </span>
                                        @endif
                                        <span class="text-xs text-gray-500">
                                            {{ $page->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                        <a href="{{ route('documentation.page', [$category->slug, $page->slug]) }}" 
                                           class="hover:text-blue-600 transition-colors">
                                            {{ $page->title }}
                                        </a>
                                    </h3>
                                    @if($page->excerpt)
                                        <p class="text-gray-600 text-sm mb-4">
                                            {{ Str::limit($page->excerpt, 150) }}
                                        </p>
                                    @endif
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center text-xs text-gray-500">
                                            <i class="fas fa-eye mr-1"></i>
                                            {{ $page->sort_order }}
                                        </div>
                                        <a href="{{ route('documentation.page', [$category->slug, $page->slug]) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Read more →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($pages->hasPages())
                        <div class="mt-8">
                            {{ $pages->links() }}
                        </div>
                    @endif
                @else
                    <div class="bg-white rounded-lg shadow-sm border p-8 text-center">
                        <div class="text-gray-400 mb-4">
                            <i class="fas fa-file-alt text-6xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No pages found</h3>
                        <p class="text-gray-600 mb-4">
                            There are no documentation pages in this category yet.
                        </p>
                        <a href="{{ route('documentation.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>
                            Add First Page
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-documentation::layouts.master>
