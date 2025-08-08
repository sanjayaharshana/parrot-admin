<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Models\Ship;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\ResourceController;
use Modules\UserPanel\Services\ResourceService;

class ShipController extends ResourceController
{
    public $icon = 'fa fa-ship';
    public $model = Ship::class;
    public $routeName = 'ships';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataView = $this->dataView();
        $gridContent = $dataView->render();

        // Get title and description from the data view
        $data = $dataView->getData();

        return view('userpanel::index', [
            'grid' => $gridContent,
            'title' => $data['title'] ?? 'Ship Management',
            'description' => $data['description'] ?? 'Manage ships with full CRUD operations'
        ]);
    }

    /**
     * Make the resource instance
     */
    protected function makeResource(): ResourceService
    {
        return (new ResourceService(Ship::class, 'ships'))
            ->title('Ship Management')
            ->description('Manage ships with full CRUD operations')

            // Welcome message with custom HTML
            ->alert('Welcome to the Ship Management System. Please provide accurate information for all fields.', 'info')

            // Basic Information Section
            ->divider('Basic Information')
            ->text('name')
                ->required()
                ->searchable()
                ->sortable()
                ->rules(['max:255'])

            ->text('ship')
                ->required()
                ->searchable()
                ->sortable()
                ->rules(['max:255'])

            // Important note card
            ->customCard(
                '<ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                    <li>Ship name should be unique and descriptive</li>
                    <li>All fields marked with * are required</li>
                    <li>Address should be complete and accurate for shipping purposes</li>
                </ul>',
                'Important Notes',
                'bg-blue-50 border border-blue-200 rounded-lg p-4'
            )

            // Location Details Section
            ->divider('Location Details')
            ->textarea('address')
                ->required()
                ->searchable()
                ->rules(['max:1000'])

            // Help section with custom HTML
            ->customCard(
                '<p class="text-sm text-gray-600">Need help? Contact our support team at
                <a href="mailto:support@example.com" class="text-blue-600 hover:text-blue-800">support@example.com</a>
                or call us at <span class="font-medium">+1-555-123-4567</span></p>',
                'Need Help?',
                'bg-gray-50 border border-gray-200 rounded-lg p-4'
            )

            // Action buttons section
            ->customCard(
                '<div class="flex space-x-4">
                    <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        <i class="fa fa-eye mr-2"></i>Preview
                    </button>
                    <button type="button" class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        <i class="fa fa-save mr-2"></i>Save Draft
                    </button>
                </div>',
                'Quick Actions',
                'bg-gray-50 border border-gray-200 rounded-lg p-4'
            )

            // Configure actions
            ->actions([
                'view' => [
                    'label' => 'View',
                    'icon' => 'fa fa-eye',
                    'class' => 'btn-sm btn-info',
                    'route' => 'show'
                ],
                'edit' => [
                    'label' => 'Edit',
                    'icon' => 'fa fa-edit',
                    'class' => 'btn-sm btn-primary',
                    'route' => 'edit'
                ],
                'delete' => [
                    'label' => 'Delete',
                    'icon' => 'fa fa-trash',
                    'class' => 'btn-sm btn-danger',
                    'route' => 'destroy',
                    'method' => 'DELETE',
                    'confirm' => true
                ]
            ])

            // Configure bulk actions
            ->bulkActions([
                'delete' => [
                    'label' => 'Delete Selected',
                    'icon' => 'fa fa-trash',
                    'class' => 'btn-danger',
                    'confirm' => true
                ]
            ]);
    }

    public function dataView()
    {
        $dataView = new \Modules\UserPanel\Services\DataViewService(new \App\Models\Ship());

        // Configure the data view
        $dataView->title('Ship Management')
            ->description('Manage ships with full CRUD operations')
            ->routePrefix('ships')
            ->perPage(15)
            ->defaultSort('id', 'desc')
            ->pagination(true)
            ->search(true)
            ->filters(true);

        // Add ID column
        $dataView->id('ID')->sortable();

        // Add name column
        $dataView->column('name', 'Ship Name')
            ->sortable()
            ->searchable();

        // Add ship column
        $dataView->column('ship', 'Ship Type')
            ->sortable()
            ->searchable();

        // Add address column with truncation for better display
        $dataView->column('address', 'Address')
            ->display(function($value) {
                return strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value;
            })
            ->searchable();

        // Add created_at column with custom formatting
//        $dataView->column('created_at', 'Created Date')
//            ->display(function($value) {
//                return $value ? date('M d, Y H:i', strtotime($value)) : 'N/A';
//            })
//            ->sortable();

        // Add updated_at column with custom formatting
//        $dataView->column('updated_at', 'Last Updated')
//            ->display(function($value) {
//                return $value ? date('M d, Y H:i', strtotime($value)) : 'N/A';
//            })
//            ->sortable();

        // Add actions column
        $dataView->actions([
            'view' => [
                'label' => 'View',
                'icon' => 'fa fa-eye',
                'class' => 'btn-sm btn-info',
                'route' => 'show'
            ],
            'edit' => [
                'label' => 'Edit',
                'icon' => 'fa fa-edit',
                'class' => 'btn-sm btn-primary',
                'route' => 'edit'
            ],
            'delete' => [
                'label' => 'Delete',
                'icon' => 'fa fa-trash',
                'class' => 'btn-sm btn-danger',
                'route' => 'destroy',
                'method' => 'DELETE',
                'confirm' => true
            ]
        ]);

        // Add bulk actions
        $dataView->bulkActions([
            'delete' => [
                'label' => 'Delete Selected',
                'icon' => 'fa fa-trash',
                'class' => 'btn-danger',
                'confirm' => true
            ]
        ]);

        // Add filters
        $dataView->addTextFilter('name', 'Ship Name');
        $dataView->addTextFilter('ship', 'Ship Type');
        $dataView->addDateRangeFilter('created_at', 'Created Date');

        // Add create button
        $dataView->createButton(route('ships.create'), 'Create New Ship');

        return $dataView;
    }
}
