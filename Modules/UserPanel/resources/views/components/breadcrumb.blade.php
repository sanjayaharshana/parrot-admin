@props(['items' => []])

<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        @foreach($items as $index => $item)
            @if($index === 0)
                <li class="inline-flex items-center">
                    <a href="{{ $item['url'] ?? route('dashboard.index') }}" 
                       class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-home mr-2"></i>
                        {{ $item['label'] ?? 'Dashboard' }}
                    </a>
                </li>
            @else
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        @if(isset($item['url']) && $index < count($items) - 1)
                            <a href="{{ $item['url'] }}" 
                               class="text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                                {{ $item['label'] }}
                            </a>
                        @else
                            <span class="text-sm font-medium text-gray-500">{{ $item['label'] }}</span>
                        @endif
                    </div>
                </li>
            @endif
        @endforeach
    </ol>
</nav>
