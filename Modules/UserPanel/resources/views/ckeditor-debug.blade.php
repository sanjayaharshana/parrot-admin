@extends('userpanel::components.layouts.master')

@section('title', 'CKEditor Debug')
@section('page-title', 'CKEditor Debug Information')

@section('content')
    <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">CKEditor Debug Information</h2>
            <p class="text-gray-600">
                This page shows debug information to help identify why CKEditor isn't working.
            </p>
        </div>

        <!-- Debug Information -->
        <div class="mb-6 bg-yellow-50 p-4 rounded-lg border border-yellow-200">
            <h3 class="text-lg font-semibold text-yellow-800 mb-2">Debug Information</h3>
            
            <div class="space-y-4">
                <div>
                    <h4 class="font-medium text-yellow-800">Resource Fields:</h4>
                    <pre class="text-sm bg-white p-3 rounded border overflow-auto">{{ print_r($debug['resource_fields'], true) }}</pre>
                </div>
                
                <div>
                    <h4 class="font-medium text-yellow-800">Form Content (First 1000 chars):</h4>
                    <pre class="text-sm bg-white p-3 rounded border overflow-auto">{{ substr($debug['form_content'], 0, 1000) }}</pre>
                </div>
            </div>
        </div>

        <!-- Test Form -->
        <form method="POST" action="{{ route('ckeditor-debug.store') }}" class="space-y-6">
            @csrf
            {!! $form->renderFormContent() !!}
            
            <div class="flex justify-end space-x-4 mt-6">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Submit Debug Form
                </button>
            </div>
        </form>
    </div>

    <!-- Manual CKEditor Test -->
    <div class="mt-8 bg-white rounded-lg shadow-lg p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Manual CKEditor Test</h3>
        <p class="text-gray-600 mb-4">This is a manual test to see if CKEditor loads at all:</p>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Manual Textarea with CKEditor:</label>
                <textarea data-ckeditor="true" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" rows="5" placeholder="This should show CKEditor if everything is working correctly..."></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Regular Textarea (for comparison):</label>
                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" rows="5" placeholder="This is a regular textarea..."></textarea>
            </div>
        </div>
    </div>

    <!-- JavaScript Debug -->
    <div class="mt-8 bg-white rounded-lg shadow-lg p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">JavaScript Debug</h3>
        <p class="text-gray-600 mb-4">Check the browser console for any JavaScript errors.</p>
        
        <div class="space-y-2">
            <button onclick="checkCKEditor()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                Check CKEditor Status
            </button>
            <button onclick="checkTextareas()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Check Textareas
            </button>
        </div>
        
        <div id="debug-output" class="mt-4 p-3 bg-gray-100 rounded text-sm font-mono"></div>
    </div>
@endsection

@push('scripts')
<script>
function checkCKEditor() {
    const output = document.getElementById('debug-output');
    let html = '<strong>CKEditor Status:</strong><br>';
    
    if (typeof ClassicEditor !== 'undefined') {
        html += '✅ ClassicEditor is loaded<br>';
    } else {
        html += '❌ ClassicEditor is NOT loaded<br>';
    }
    
    if (window.ckeditorInstances) {
        html += `✅ CKEditor instances found: ${Object.keys(window.ckeditorInstances).length}<br>`;
    } else {
        html += '❌ No CKEditor instances found<br>';
    }
    
    output.innerHTML = html;
}

function checkTextareas() {
    const output = document.getElementById('debug-output');
    const textareas = document.querySelectorAll('textarea');
    let html = '<strong>Textarea Analysis:</strong><br>';
    
    textareas.forEach((textarea, index) => {
        const hasDataCkeditor = textarea.hasAttribute('data-ckeditor');
        const isVisible = textarea.style.display !== 'none';
        const id = textarea.id || 'no-id';
        
        html += `Textarea ${index + 1}: ID="${id}", data-ckeditor="${hasDataCkeditor}", visible="${isVisible}"<br>`;
    });
    
    output.innerHTML = html;
}

// Auto-check on page load
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        checkCKEditor();
        checkTextareas();
    }, 1000);
});
</script>
@endpush
