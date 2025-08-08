<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\ResourceController;
use Modules\UserPanel\Services\ResourceService;

class ExampleTabController extends ResourceController
{
    public $icon = 'fa fa-user';
    public $model = User::class;
    public $routeName = 'users';

    /**
     * Make the resource instance with tabs
     */
    protected function makeResource(): ResourceService
    {
        return (new ResourceService(User::class, 'users'))
            ->title('User Management')
            ->description('Manage users with organized tabbed forms')

            // Enable tabs for better organization
            ->enableTabs()

            // Basic Information Tab
            ->tab('basic', 'Basic Information', 'fa fa-user')
                ->text('name')
                    ->required()
                    ->searchable()
                    ->sortable()
                    ->rules(['max:255'])
                ->text('email')
                    ->required()
                    ->searchable()
                    ->sortable()
                    ->rules(['email', 'max:255'])
                ->password('password')
                    ->required()
                    ->rules(['min:8'])
                ->divider('User Guidelines')
                ->alert('Email addresses must be unique and passwords must be at least 8 characters long.', 'info')
                ->end()

            // Profile Details Tab
            ->tab('profile', 'Profile Details', 'fa fa-id-card')
                ->textarea('bio')
                    ->searchable()
                    ->rules(['max:1000'])
                ->select('role')
                    ->required()
                    ->searchable()
                    ->sortable()
                    ->options([
                        'user' => 'Regular User',
                        'admin' => 'Administrator',
                        'moderator' => 'Moderator'
                    ])
                    ->rules(['in:user,admin,moderator'])
                ->checkbox('is_active')
                    ->searchable()
                    ->sortable()
                ->divider('Role Information')
                ->customHtml(
                    '<div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-medium text-blue-800 mb-2">Role Descriptions:</h4>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li><strong>Regular User:</strong> Basic access to the system</li>
                            <li><strong>Moderator:</strong> Can manage content and users</li>
                            <li><strong>Administrator:</strong> Full system access</li>
                        </ul>
                    </div>',
                    'Role Descriptions',
                    'bg-blue-50 border border-blue-200 rounded-lg p-4'
                )
                ->end()

            // Contact Information Tab
            ->tab('contact', 'Contact Information', 'fa fa-address-book')
                ->text('phone')
                    ->searchable()
                    ->rules(['max:20'])
                ->text('address')
                    ->searchable()
                    ->rules(['max:500'])
                ->text('city')
                    ->searchable()
                    ->rules(['max:100'])
                ->text('country')
                    ->searchable()
                    ->rules(['max:100'])
                ->divider('Contact Guidelines')
                ->alert('Please provide accurate contact information for better communication.', 'warning')
                ->end()

            // Settings Tab
            ->tab('settings', 'User Settings', 'fa fa-cog')
                ->checkbox('email_notifications')
                    ->searchable()
                ->checkbox('sms_notifications')
                    ->searchable()
                ->select('timezone')
                    ->searchable()
                    ->options([
                        'UTC' => 'UTC (Coordinated Universal Time)',
                        'EST' => 'EST (Eastern Standard Time)',
                        'PST' => 'PST (Pacific Standard Time)',
                        'GMT' => 'GMT (Greenwich Mean Time)'
                    ])
                ->divider('Notification Preferences')
                ->customHtml(
                    '<div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h4 class="font-medium text-green-800 mb-2">Notification Settings:</h4>
                        <p class="text-sm text-green-700">Configure how you want to receive updates and notifications from the system.</p>
                    </div>',
                    'Notification Settings',
                    'bg-green-50 border border-green-200 rounded-lg p-4'
                )
                ->end()

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
                'activate' => [
                    'label' => 'Activate Selected',
                    'icon' => 'fa fa-check',
                    'class' => 'btn-success'
                ],
                'deactivate' => [
                    'label' => 'Deactivate Selected',
                    'icon' => 'fa fa-times',
                    'class' => 'btn-warning'
                ],
                'delete' => [
                    'label' => 'Delete Selected',
                    'icon' => 'fa fa-trash',
                    'class' => 'btn-danger',
                    'confirm' => true
                ]
            ]);
    }

    /**
     * Configure the data view
     */
    public function dataView()
    {
        $dataView = new \Modules\UserPanel\Services\DataViewService(new \App\Models\User());

        // Configure the data view
        $dataView->title('User Management')
            ->description('Manage users with organized tabbed forms')
            ->routePrefix('users')
            ->perPage(15)
            ->defaultSort('id', 'desc')
            ->pagination(true)
            ->search(true)
            ->filters(true);

        // Add columns
        $dataView->id('ID')->sortable();

        $dataView->column('name', 'Name')
            ->sortable()
            ->searchable();

        $dataView->column('email', 'Email')
            ->sortable()
            ->searchable();

        $dataView->column('role', 'Role')
            ->sortable()
            ->searchable()
            ->display(function($value) {
                $roles = [
                    'user' => '<span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">User</span>',
                    'moderator' => '<span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">Moderator</span>',
                    'admin' => '<span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Admin</span>'
                ];
                return $roles[$value] ?? $value;
            });

        $dataView->column('is_active', 'Status')
            ->sortable()
            ->display(function($value) {
                return $value ? 
                    '<span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Active</span>' : 
                    '<span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Inactive</span>';
            });

        $dataView->column('created_at', 'Created Date')
            ->display(function($value) {
                return $value ? date('M d, Y H:i', strtotime($value)) : 'N/A';
            })
            ->sortable();

        // Add actions
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
            'activate' => [
                'label' => 'Activate Selected',
                'icon' => 'fa fa-check',
                'class' => 'btn-success'
            ],
            'deactivate' => [
                'label' => 'Deactivate Selected',
                'icon' => 'fa fa-times',
                'class' => 'btn-warning'
            ],
            'delete' => [
                'label' => 'Delete Selected',
                'icon' => 'fa fa-trash',
                'class' => 'btn-danger',
                'confirm' => true
            ]
        ]);

        // Add filters
        $dataView->addTextFilter('name', 'Name');
        $dataView->addTextFilter('email', 'Email');
        $dataView->addSelectFilter('role', 'Role', [
            'user' => 'Regular User',
            'moderator' => 'Moderator',
            'admin' => 'Administrator'
        ]);
        $dataView->addSelectFilter('is_active', 'Status', [
            '1' => 'Active',
            '0' => 'Inactive'
        ]);
        $dataView->addDateRangeFilter('created_at', 'Created Date');

        // Add create button
        $dataView->createButton(route('users.create'), 'Create New User');

        return $dataView;
    }
}
