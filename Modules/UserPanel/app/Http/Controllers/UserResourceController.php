<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\ResourceController;
use Modules\UserPanel\Services\ResourceService;

class UserResourceController extends ResourceController
{
    public $icon = 'fa fa-users';
    public $model = User::class;
    public $routeName = 'users';

    /**
     * Make the resource instance
     */
    protected function makeResource(): ResourceService
    {
        return (new ResourceService(User::class, 'users'))
            ->title('User Management')
            ->description('Manage users with full CRUD operations')
            
            // Define fields
            ->text('name')
                ->required()
                ->searchable()
                ->sortable()
                ->rules(['max:255'])
            
            ->email('email')
                ->required()
                ->searchable()
                ->sortable()
                ->rules(['email', 'unique:users,email'])
            
            ->password('password')
                ->required()
                ->rules(['min:8'])
            
            ->select('email_verified_at')
                ->filterable([
                    'type' => 'select',
                    'options' => [
                        'verified' => 'Verified',
                        'unverified' => 'Not Verified'
                    ]
                ])
                ->display(function($value) {
                    if ($value) {
                        return '<span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Verified</span>';
                    }
                    return '<span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Unverified</span>';
                })
            
            ->date('created_at')
                ->sortable()
                ->display(function($value) {
                    return $value ? date('M d, Y', strtotime($value)) : 'N/A';
                })
            
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
                ],
                'verify' => [
                    'label' => 'Verify Selected',
                    'icon' => 'fa fa-check',
                    'class' => 'btn-success'
                ]
            ]);
    }
} 