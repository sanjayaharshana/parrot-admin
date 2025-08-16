@extends('userpanel::components.layouts.master')

@section('title', 'System Settings')
@section('page-title', 'System Settings')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">System Settings</h1>
                <p class="text-gray-600 mt-2">Configure your application settings and preferences</p>
            </div>
            <div class="flex space-x-3">
                <button type="button" onclick="resetSettings()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fa fa-refresh mr-2"></i>
                    Reset to Defaults
                </button>
                <button type="submit" form="settingsForm" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fa fa-save mr-2"></i>
                    Save All Settings
                </button>
            </div>
        </div>
    </div>

    <!-- Settings Form -->
    <form id="settingsForm" action="{{ route('settings.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Settings Groups -->
        @if($groups && count($groups) > 0)
            @foreach($groups as $groupKey => $groupInfo)
                @if(isset($settings[$groupKey]) && $settings[$groupKey] && $settings[$groupKey]->count() > 0)
                <div class="bg-white shadow rounded-lg">
                    <!-- Group Header -->
                    <div class="px-6 py-4 border-b border-gray-200">
                                                    <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="{{ $groupInfo['icon'] ?? 'fa fa-cog' }} text-2xl text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $groupInfo['title'] ?? 'Settings' }}</h3>
                                    <p class="text-sm text-gray-500">{{ $groupInfo['description'] ?? 'Configuration options' }}</p>
                                </div>
                            </div>
                    </div>

                    <!-- Group Settings -->
                    <div class="px-6 py-4 space-y-6">
                        @foreach($settings[$groupKey] as $setting)
                            @if($setting)
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                                <!-- Setting Label and Description -->
                                <div class="lg:col-span-1">
                                    <label for="setting_{{ $setting->key }}" class="block text-sm font-medium text-gray-700">
                                        {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                                        @if($setting->is_public)
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Public
                                            </span>
                                        @endif
                                    </label>
                                    @if($setting->description)
                                        <p class="mt-1 text-sm text-gray-500">{{ $setting->description }}</p>
                                    @endif
                                </div>

                                <!-- Setting Input -->
                                <div class="lg:col-span-2">
                                    @switch($setting->type)
                                        @case('boolean')
                                            <div class="flex items-center">
                                                <input type="checkbox" 
                                                       id="setting_{{ $setting->key }}" 
                                                       name="settings[{{ $setting->key }}]" 
                                                       value="1" 
                                                       {{ $setting->value ? 'checked' : '' }}
                                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                <label for="setting_{{ $setting->key }}" class="ml-2 text-sm text-gray-700">
                                                    Enable {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                                                </label>
                                            </div>
                                            @break

                                        @case('select')
                                            @if($setting->key === 'mail_driver')
                                                <select id="setting_{{ $setting->key }}" 
                                                        name="settings[{{ $setting->key }}]" 
                                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                                    <option value="smtp" {{ $setting->value === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                                    <option value="mailgun" {{ $setting->value === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                                    <option value="ses" {{ $setting->value === 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                                    <option value="sendmail" {{ $setting->value === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                                </select>
                                            @elseif($setting->key === 'currency')
                                                <select id="setting_{{ $setting->key }}" 
                                                        name="settings[{{ $setting->key }}]" 
                                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                                    <option value="USD" {{ $setting->value === 'USD' ? 'selected' : '' }}>USD ($)</option>
                                                    <option value="EUR" {{ $setting->value === 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                                    <option value="GBP" {{ $setting->value === 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                                                    <option value="JPY" {{ $setting->value === 'JPY' ? 'selected' : '' }}>JPY (¥)</option>
                                                </select>
                                            @endif
                                            @break

                                        @case('color')
                                            <div class="flex items-center space-x-3">
                                                <input type="color" 
                                                       id="setting_{{ $setting->key }}" 
                                                       name="settings[{{ $setting->key }}]" 
                                                       value="{{ $setting->value }}" 
                                                       class="h-10 w-20 border border-gray-300 rounded-md cursor-pointer">
                                                <input type="text" 
                                                       value="{{ $setting->value }}" 
                                                       class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                       placeholder="#000000"
                                                       onchange="document.getElementById('setting_{{ $setting->key }}').value = this.value">
                                            </div>
                                            @break

                                        @case('file')
                                            <div class="flex items-center space-x-3">
                                                <input type="text" 
                                                       id="setting_{{ $setting->key }}" 
                                                       name="settings[{{ $setting->key }}]" 
                                                       value="{{ $setting->value }}" 
                                                       class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                       placeholder="Enter file path or URL">
                                                <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    <i class="fa fa-upload mr-2"></i>
                                                    Upload
                                                </button>
                                            </div>
                                            @break

                                        @case('text')
                                            <textarea id="setting_{{ $setting->key }}" 
                                                      name="settings[{{ $setting->key }}]" 
                                                      rows="3" 
                                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                      placeholder="Enter {{ str_replace('_', ' ', $setting->key) }}">{{ $setting->value }}</textarea>
                                            @break

                                        @default
                                            <input type="text" 
                                                   id="setting_{{ $setting->key }}" 
                                                   name="settings[{{ $setting->key }}]" 
                                                   value="{{ $setting->value }}" 
                                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                   placeholder="Enter {{ str_replace('_', ' ', $setting->key) }}">
                                    @endswitch
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
        @else
            <!-- No Settings Available -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-center">
                    <i class="fa fa-cog text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Settings Available</h3>
                    <p class="text-gray-500 mb-4">There are no settings configured yet. Please run the database seeder to populate default settings.</p>
                    <button type="button" onclick="window.location.href='{{ route('settings.reset') }}'" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fa fa-database mr-2"></i>
                        Initialize Settings
                    </button>
                </div>
            </div>
        @endif
    </form>
</div>

<!-- Reset Settings Confirmation Modal -->
<div id="resetModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fa fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Reset Settings</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Are you sure you want to reset all settings to their default values? This action cannot be undone.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="confirmReset" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Yes, Reset All Settings
                </button>
                <button id="cancelReset" class="mt-3 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function resetSettings() {
    document.getElementById('resetModal').classList.remove('hidden');
}

document.getElementById('confirmReset').addEventListener('click', function() {
    window.location.href = '{{ route("settings.reset") }}';
});

document.getElementById('cancelReset').addEventListener('click', function() {
    document.getElementById('resetModal').classList.add('hidden');
});

// Close modal when clicking outside
document.getElementById('resetModal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
    }
});

// Color picker synchronization
document.querySelectorAll('input[type="color"]').forEach(function(colorInput) {
    const textInput = colorInput.nextElementSibling;
    if (textInput && textInput.type === 'text') {
        colorInput.addEventListener('input', function() {
            textInput.value = this.value;
        });
        textInput.addEventListener('input', function() {
            colorInput.value = this.value;
        });
    }
});
</script>
@endsection
