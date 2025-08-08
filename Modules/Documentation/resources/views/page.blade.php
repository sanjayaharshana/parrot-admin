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
            <!-- Breadcrumb -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('documentation.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <i class="fas fa-home mr-2"></i>
                            Documentation
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('documentation.category', $category->slug) }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                {{ $category->name }}
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">{{ $page->title }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="bg-white rounded-lg shadow-sm border p-8 mb-8">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center" 
                                 style="background-color: {{ $category->color }}20;">
                                <i class="{{ $category->icon }} text-xl" style="color: {{ $category->color }}"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                  style="background-color: {{ $category->color }}20; color: {{ $category->color }};">
                                {{ $category->name }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if($page->is_featured)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-star mr-1"></i>
                                Featured
                            </span>
                        @endif
                        <a href="{{ route('documentation.edit', $page->id) }}" 
                           class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-edit mr-1"></i>
                            Edit
                        </a>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $page->title }}</h1>
                @if($page->excerpt)
                    <p class="text-lg text-gray-600 mb-4">{{ $page->excerpt }}</p>
                @endif
                <div class="flex items-center text-sm text-gray-500">
                    <i class="fas fa-clock mr-2"></i>
                    Last updated {{ $page->updated_at->diffForHumans() }}
                    @if($page->published_at)
                        <span class="mx-2">•</span>
                        <i class="fas fa-calendar mr-2"></i>
                        Published {{ $page->published_at->format('M j, Y') }}
                    @endif
                </div>
            </div>

            <!-- Page Content -->
            <div class="bg-white rounded-lg shadow-sm border p-8 mb-8">
                <div class="prose max-w-none">
                    {!! $page->content !!}
                </div>
            </div>

            <!-- Related Pages -->
            @if($relatedPages->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">
                        <i class="fas fa-link mr-2"></i>
                        Related Pages
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($relatedPages as $relatedPage)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                                <h3 class="font-semibold text-gray-900 mb-2">
                                    <a href="{{ route('documentation.page', [$category->slug, $relatedPage->slug]) }}" 
                                       class="hover:text-blue-600 transition-colors">
                                        {{ $relatedPage->title }}
                                    </a>
                                </h3>
                                @if($relatedPage->excerpt)
                                    <p class="text-sm text-gray-600">
                                        {{ Str::limit($relatedPage->excerpt, 100) }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-documentation::layouts.master>
