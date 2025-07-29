<div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Sales Chart</h3>
    <div class="bg-gray-50 rounded-lg p-4 h-48 flex items-end justify-between">
        @foreach($chartData['datasets'][0]['data'] as $index => $value)
        <div class="flex flex-col items-center">
            <div class="bg-blue-500 rounded-t w-8 mb-2" style="height: {{ ($value / max($chartData['datasets'][0]['data'])) * 120 }}px;"></div>
            <span class="text-xs text-gray-600">{{ $chartData['labels'][$index] }}</span>
        </div>
        @endforeach
    </div>
    <div class="mt-4 flex items-center justify-between">
        <div class="flex items-center">
            <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
            <span class="text-sm text-gray-600">{{ $chartData['datasets'][0]['label'] }}</span>
        </div>
        <button type="button" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
            View Details →
        </button>
    </div>
</div> 