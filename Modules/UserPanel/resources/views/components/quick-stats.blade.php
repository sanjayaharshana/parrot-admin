<div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg p-4 border border-indigo-200">
    <h4 class="text-sm font-medium text-indigo-900 mb-3">{{ $title }}</h4>
    <div class="space-y-2">
        @foreach($items as $item)
        <div class="flex items-center justify-between">
            <span class="text-xs text-indigo-700">{{ $item['label'] }}</span>
            <span class="text-xs font-semibold text-indigo-900">{{ $item['value'] }}</span>
        </div>
        @endforeach
    </div>
</div> 