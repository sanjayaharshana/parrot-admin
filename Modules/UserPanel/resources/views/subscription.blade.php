@extends('userpanel::components.layouts.master')

@section('title', 'Subscription')
@section('page-title', 'Subscription & Billing')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Current Plan -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Current Plan</h3>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                <i class="fas fa-check-circle mr-1"></i> Active
            </span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="text-3xl font-bold gradient-text mb-2">{{ $subscription['plan'] }}</div>
                <p class="text-gray-600">Current Plan</p>
            </div>
            
            <div class="text-center">
                <div class="text-3xl font-bold text-gray-900 mb-2">$29</div>
                <p class="text-gray-600">Monthly</p>
            </div>
            
            <div class="text-center">
                <div class="text-3xl font-bold text-gray-900 mb-2">{{ $subscription['next_billing']->format('M j') }}</div>
                <p class="text-gray-600">Next Billing</p>
            </div>
        </div>
    </div>

    <!-- Plan Features -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Your Plan Features</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($subscription['features'] as $feature)
            <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                <i class="fas fa-check text-green-500 w-5 h-5 mr-3"></i>
                <span class="text-gray-700">{{ $feature }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Billing Information -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Billing Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                <div class="flex items-center p-3 border border-gray-300 rounded-md">
                    <i class="fab fa-cc-visa text-purple-600 w-6 h-6 mr-3"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Visa ending in 4242</p>
                        <p class="text-xs text-gray-500">Expires 12/25</p>
                    </div>
                    <button class="ml-auto text-purple-600 hover:text-purple-700 text-sm font-medium transition-colors">
                        Update
                    </button>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Billing Address</label>
                <div class="p-3 border border-gray-300 rounded-md">
                    <p class="text-sm text-gray-900">John Doe</p>
                    <p class="text-sm text-gray-600">123 Main Street</p>
                    <p class="text-sm text-gray-600">New York, NY 10001</p>
                    <button class="mt-2 text-purple-600 hover:text-purple-700 text-sm font-medium transition-colors">
                        Update Address
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Billing History -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Billing History</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Dec 1, 2024</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Pro Plan - Monthly</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$29.00</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="#" class="text-purple-600 hover:text-purple-700 text-sm font-medium transition-colors">Download</a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Nov 1, 2024</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Pro Plan - Monthly</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$29.00</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="#" class="text-purple-600 hover:text-purple-700 text-sm font-medium transition-colors">Download</a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Oct 1, 2024</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Pro Plan - Monthly</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$29.00</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="#" class="text-purple-600 hover:text-purple-700 text-sm font-medium transition-colors">Download</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Plan Comparison -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Available Plans</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Basic Plan -->
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                <div class="text-center mb-6">
                    <h4 class="text-xl font-semibold text-gray-900 mb-2">Basic</h4>
                    <div class="text-3xl font-bold text-gray-900 mb-1">$9</div>
                    <p class="text-gray-600">per month</p>
                </div>
                
                <ul class="space-y-3 mb-6">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 w-4 h-4 mr-2"></i>
                        <span class="text-sm text-gray-700">5 Projects</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 w-4 h-4 mr-2"></i>
                        <span class="text-sm text-gray-700">Basic Analytics</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 w-4 h-4 mr-2"></i>
                        <span class="text-sm text-gray-700">Email Support</span>
                    </li>
                </ul>
                
                <button class="w-full bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700 transition-colors">
                    Current Plan
                </button>
            </div>
            
            <!-- Pro Plan (Current) -->
            <div class="border-2 border-purple-500 rounded-lg p-6 relative hover:shadow-md transition-shadow">
                <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                    <span class="gradient-bg text-white px-3 py-1 rounded-full text-xs font-medium">Current</span>
                </div>
                
                <div class="text-center mb-6">
                    <h4 class="text-xl font-semibold text-gray-900 mb-2">Pro</h4>
                    <div class="text-3xl font-bold gradient-text mb-1">$29</div>
                    <p class="text-gray-600">per month</p>
                </div>
                
                <ul class="space-y-3 mb-6">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 w-4 h-4 mr-2"></i>
                        <span class="text-sm text-gray-700">Unlimited Projects</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 w-4 h-4 mr-2"></i>
                        <span class="text-sm text-gray-700">Advanced Analytics</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 w-4 h-4 mr-2"></i>
                        <span class="text-sm text-gray-700">Priority Support</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 w-4 h-4 mr-2"></i>
                        <span class="text-sm text-gray-700">Custom Branding</span>
                    </li>
                </ul>
                
                <button class="w-full gradient-bg text-white py-2 px-4 rounded-md hover-gradient transition-all duration-200">
                    Current Plan
                </button>
            </div>
            
            <!-- Enterprise Plan -->
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                <div class="text-center mb-6">
                    <h4 class="text-xl font-semibold text-gray-900 mb-2">Enterprise</h4>
                    <div class="text-3xl font-bold text-gray-900 mb-1">$99</div>
                    <p class="text-gray-600">per month</p>
                </div>
                
                <ul class="space-y-3 mb-6">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 w-4 h-4 mr-2"></i>
                        <span class="text-sm text-gray-700">Everything in Pro</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 w-4 h-4 mr-2"></i>
                        <span class="text-sm text-gray-700">Team Management</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 w-4 h-4 mr-2"></i>
                        <span class="text-sm text-gray-700">API Access</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 w-4 h-4 mr-2"></i>
                        <span class="text-sm text-gray-700">Dedicated Support</span>
                    </li>
                </ul>
                
                <button class="w-full bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700 transition-colors">
                    Upgrade
                </button>
            </div>
        </div>
    </div>

    <!-- Subscription Actions -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Subscription Actions</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <button class="flex items-center justify-center p-4 border border-gray-300 rounded-lg hover:bg-yellow-50 hover:border-yellow-200 transition-colors">
                <i class="fas fa-pause text-yellow-500 w-5 h-5 mr-3"></i>
                <span class="font-medium text-gray-700">Pause Subscription</span>
            </button>
            
            <button class="flex items-center justify-center p-4 border border-gray-300 rounded-lg hover:bg-purple-50 hover:border-purple-200 transition-colors">
                <i class="fas fa-download text-purple-500 w-5 h-5 mr-3"></i>
                <span class="font-medium text-gray-700">Download Invoices</span>
            </button>
            
            <button class="flex items-center justify-center p-4 border border-gray-300 rounded-lg hover:bg-green-50 hover:border-green-200 transition-colors">
                <i class="fas fa-credit-card text-green-500 w-5 h-5 mr-3"></i>
                <span class="font-medium text-gray-700">Update Payment Method</span>
            </button>
            
            <button class="flex items-center justify-center p-4 border border-red-300 rounded-lg hover:bg-red-50 transition-colors">
                <i class="fas fa-times text-red-500 w-5 h-5 mr-3"></i>
                <span class="font-medium text-red-700">Cancel Subscription</span>
            </button>
        </div>
    </div>
</div>
@endsection 