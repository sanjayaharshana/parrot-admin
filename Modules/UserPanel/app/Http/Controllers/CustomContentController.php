<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\BaseController;
use Modules\UserPanel\Services\FormService;
use Modules\UserPanel\Services\LayoutService;

class CustomContentController extends BaseController
{
    // Set to true to show in sidebar
    public $showInSidebar = true;

    public function index()
    {
        // Create form and layout services
        $form = new FormService();
        $layout = new LayoutService();
        $layout->setFormService($form);
        
        // Section with custom HTML
        $layout->section('Custom HTML Content', 'Demonstrating custom HTML within layouts', function ($form, $section) {
            $section->addField(
                $form->text()
                    ->name('title')
                    ->label('Title')
                    ->required()
            );
            
            // Add custom HTML
            $section->addHtml('
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Custom HTML Notice</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>This is custom HTML content added directly to the layout. You can include any HTML, JavaScript, or CSS here.</p>
                            </div>
                        </div>
                    </div>
                </div>
            ');
            
            $section->addField(
                $form->textarea()
                    ->name('description')
                    ->label('Description')
                    ->required()
            );
        });
        
        // Row with custom HTML and views
        $layout->row()
            ->column('1/2', function ($form, $column) {
                $column->addField(
                    $form->text()
                        ->name('name')
                        ->label('Name')
                        ->required()
                );
                
                // Add custom HTML with JavaScript
                $column->addHtml('
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <h4 class="text-sm font-medium text-green-800 mb-2">Interactive Element</h4>
                        <button type="button" onclick="alert(\'Hello from custom HTML!\')" 
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                            Click Me!
                        </button>
                    </div>
                ');
                
                $column->addField(
                    $form->email()
                        ->name('email')
                        ->label('Email')
                        ->required()
                );
            })
            ->column('1/2', function ($form, $column) {
                // Add a custom Blade view
                $column->addView('userpanel::components.custom-stats', [
                    'stats' => [
                        ['label' => 'Total Users', 'value' => '1,234', 'icon' => 'users'],
                        ['label' => 'Active Projects', 'value' => '56', 'icon' => 'folder'],
                        ['label' => 'Completed Tasks', 'value' => '789', 'icon' => 'check']
                    ]
                ]);
                
                $column->addField(
                    $form->select()
                        ->name('category')
                        ->label('Category')
                        ->options([
                            'tech' => 'Technology',
                            'design' => 'Design',
                            'business' => 'Business'
                        ])
                        ->required()
                );
            });
        
        // Custom HTML standalone
        $layout->html('
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg p-6 text-white mb-6">
                <h3 class="text-xl font-bold mb-2">Custom HTML Section</h3>
                <p class="text-purple-100">This is a standalone custom HTML section with gradient background and custom styling.</p>
                <div class="mt-4 flex space-x-4">
                    <button type="button" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-lg transition-all">
                        Action 1
                    </button>
                    <button type="button" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-lg transition-all">
                        Action 2
                    </button>
                </div>
            </div>
        ');
        
        // Custom Blade view standalone
        $layout->view('userpanel::components.custom-chart', [
            'chartData' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                'datasets' => [
                    [
                        'label' => 'Sales',
                        'data' => [12, 19, 3, 5, 2],
                        'backgroundColor' => 'rgba(59, 130, 246, 0.5)'
                    ]
                ]
            ]
        ]);
        
        return view('userpanel::custom-content', [
            'layout' => $layout->render()
        ]);
    }

    public function advanced()
    {
        $form = new FormService();
        $layout = new LayoutService();
        $layout->setFormService($form);
        
        // Card with mixed content
        $layout->card('Mixed Content Example', function ($form, $card) {
            $card->addField(
                $form->text()
                    ->name('project_name')
                    ->label('Project Name')
                    ->required()
            );
            
            // Add custom component
            $card->addComponent('progress-bar', [
                'progress' => 75,
                'label' => 'Project Progress',
                'color' => 'blue'
            ]);
            
            $card->addField(
                $form->textarea()
                    ->name('notes')
                    ->label('Notes')
            );
            
            // Add custom HTML with dynamic data
            $card->addHtml('
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium text-yellow-800">Important Notice</span>
                    </div>
                    <p class="mt-2 text-sm text-yellow-700">
                        This project is currently in review phase. Please ensure all documentation is complete.
                    </p>
                </div>
            ');
        });
        
        // Grid with custom content
        $layout->grid(3, 4)
            ->item(function ($form, $item) {
                $item->addField(
                    $form->text()
                        ->name('field1')
                        ->label('Field 1')
                        ->required()
                );
            })
            ->item(function ($form, $item) {
                // Add custom view with data
                $item->addView('userpanel::components.quick-stats', [
                    'title' => 'Quick Stats',
                    'items' => [
                        ['label' => 'Views', 'value' => '1.2k'],
                        ['label' => 'Likes', 'value' => '234'],
                        ['label' => 'Shares', 'value' => '45']
                    ]
                ]);
            })
            ->item(function ($form, $item) {
                $item->addField(
                    $form->text()
                        ->name('field3')
                        ->label('Field 3')
                        ->required()
                );
            });
        
        // Custom HTML with complex structure
        $layout->html('
            <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Complex Custom HTML</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-medium text-blue-900">Feature 1</h4>
                        <p class="text-blue-700 text-sm mt-1">Description of feature 1</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h4 class="font-medium text-green-900">Feature 2</h4>
                        <p class="text-green-700 text-sm mt-1">Description of feature 2</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <h4 class="font-medium text-purple-900">Feature 3</h4>
                        <p class="text-purple-700 text-sm mt-1">Description of feature 3</p>
                    </div>
                </div>
                <div class="mt-4 flex justify-end space-x-2">
                    <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                        Cancel
                    </button>
                    <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                        Save
                    </button>
                </div>
            </div>
        ');
        
        return view('userpanel::custom-content', [
            'layout' => $layout->render()
        ]);
    }
} 