@extends('userpanel::components.layouts.master')

@section('title', 'Layout Service Demo')
@section('page-title', 'Separate Layout Management Demo')

@section('content')
<div class="space-y-6">
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    This demonstrates the separate LayoutService that allows you to create layouts independently from forms and bind them together later.
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Layout Demo -->
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-layer-group mr-2"></i>Layout Service Demo
            </h3>
            <p class="text-sm text-gray-600 mb-4">
                This form was created using the separate LayoutService with independent layout building and field binding.
            </p>
            
            <form method="POST" class="space-y-6">
                <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                    {!! $layout !!}
                    
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            <i class="fas fa-paper-plane mr-2"></i>Submit Layout Form
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Traditional Form Demo -->
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-list-alt mr-2"></i>Traditional FormService Demo
            </h3>
            <p class="text-sm text-gray-600 mb-4">
                This form was created using the traditional FormService with integrated layout management.
            </p>
            
            {!! $form !!}
        </div>
    </div>

    <!-- Code Examples -->
    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            <i class="fas fa-code mr-2"></i>Code Examples
        </h3>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Layout Service Example -->
            <div>
                <h4 class="text-md font-medium text-gray-800 mb-2">LayoutService Usage</h4>
                <div class="bg-gray-900 rounded-lg p-4 text-sm text-gray-300 overflow-x-auto">
                    <pre><code>// Create layout first
$layout = new LayoutService();

// Build layout structure
$section = $layout->section('Personal Info');
$row = $layout->row();
$row->column(6);
$row->column(6);

// Create form fields
$form = new FormService();
$fields = $form->text()->name('name');

// Bind fields to layout
$section->addField($fields);

$html = $layout->render();</code></pre>
                </div>
            </div>

            <!-- Traditional Example -->
            <div>
                <h4 class="text-md font-medium text-gray-800 mb-2">Traditional FormService</h4>
                <div class="bg-gray-900 rounded-lg p-4 text-sm text-gray-300 overflow-x-auto">
                    <pre><code>// Create form with integrated layout
$form = new FormService();

$section = $form->section('Personal Info');
$section->addField(
    $form->text()->name('name')
);

$html = $form->renderForm();</code></pre>
                </div>
            </div>
        </div>
    </div>

    <!-- Benefits -->
    <div class="bg-green-50 border-l-4 border-green-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-400"></i>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-green-800">Benefits of Separate Layout Management</h4>
                <ul class="mt-2 text-sm text-green-700 list-disc list-inside space-y-1">
                    <li><strong>Separation of Concerns:</strong> Layout logic is independent from form logic</li>
                    <li><strong>Reusability:</strong> Same layout can be used with different forms</li>
                    <li><strong>Flexibility:</strong> Build complex layouts without worrying about form fields</li>
                    <li><strong>Maintainability:</strong> Easier to modify layouts without affecting form logic</li>
                    <li><strong>Testing:</strong> Test layouts and forms independently</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection 