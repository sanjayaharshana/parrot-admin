@extends('userpanel::components.layouts.master')

@section('title', 'Preloader Demo')
@section('page-title', 'Preloader Demo')

@section('content')
    <!-- Breadcrumb -->
    <x-userpanel::breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Preloader Demo']
    ]" />

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Preloader Demo</h1>
        <p class="mt-2 text-gray-600">Test the preloader functionality that shows only on the right side (not the sidebar)</p>
    </div>

    <!-- Demo Content -->
    <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Manual Control -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-800">Manual Control</h3>
                <div class="space-y-3">
                    <button onclick="showUserPanelPreloader()" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        Show Preloader
                    </button>
                    <button onclick="hideUserPanelPreloader()" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        Hide Preloader
                    </button>
                </div>
            </div>

            <!-- Auto-triggered -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-800">Auto-triggered</h3>
                <div class="space-y-3">
                    <button onclick="testFetchRequest()" 
                            class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        Test Fetch Request
                    </button>
                    <button onclick="testAjaxRequest()" 
                            class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        Test AJAX Request
                    </button>
                </div>
            </div>
        </div>

        <!-- Information -->
        <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <h4 class="font-semibold text-blue-800 mb-2">How it works:</h4>
            <ul class="text-blue-700 space-y-1 text-sm">
                <li>• The preloader automatically shows when the page loads and hides after 500ms</li>
                <li>• It automatically shows during fetch/AJAX requests</li>
                <li>• It only covers the main content area (right side), not the sidebar</li>
                <li>• It's responsive and works on mobile devices</li>
                <li>• You can manually control it using the buttons above</li>
            </ul>
        </div>
    </div>

    <script>
        // Debug preloader status
        function debugPreloader() {
            const preloader = document.getElementById('preloader');
            console.log('Preloader element:', preloader);
            if (preloader) {
                console.log('Preloader classes:', preloader.className);
                console.log('Preloader hidden:', preloader.classList.contains('hidden'));
                console.log('Preloader visible:', preloader.style.display !== 'none');
            }
        }

        // Test preloader functions
        function testPreloaderFunctions() {
            console.log('Testing preloader functions...');
            console.log('showUserPanelPreloader:', typeof window.showUserPanelPreloader);
            console.log('hideUserPanelPreloader:', typeof window.hideUserPanelPreloader);
            console.log('forceHidePreloader:', typeof window.forceHidePreloader);
        }

        // Debug on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Page loaded, debugging preloader...');
            debugPreloader();
            testPreloaderFunctions();
        });

        function testFetchRequest() {
            // Simulate a fetch request
            fetch('/api/test-endpoint')
                .catch(() => {
                    // Ignore errors for demo purposes
                    console.log('Fetch request completed');
                });
        }

        function testAjaxRequest() {
            // Simulate an AJAX request
            const event = new CustomEvent('ajax:start');
            document.dispatchEvent(event);
            
            setTimeout(() => {
                const event = new CustomEvent('ajax:end');
                document.dispatchEvent(event);
            }, 2000);
        }

        // Add debug buttons
        function addDebugButtons() {
            const debugDiv = document.createElement('div');
            debugDiv.className = 'mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg';
            debugDiv.innerHTML = `
                <h4 class="font-semibold text-yellow-800 mb-2">Debug Controls:</h4>
                <div class="space-y-2">
                    <button onclick="debugPreloader()" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-sm">Debug Preloader</button>
                    <button onclick="testPreloaderFunctions()" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-sm">Test Functions</button>
                    <button onclick="window.forceHidePreloader()" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">Force Hide</button>
                </div>
            `;
            document.querySelector('.bg-white.rounded-lg').appendChild(debugDiv);
        }

        // Add debug section when page loads
        document.addEventListener('DOMContentLoaded', addDebugButtons);
    </script>
@endsection
