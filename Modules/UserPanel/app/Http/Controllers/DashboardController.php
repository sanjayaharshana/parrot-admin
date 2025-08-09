<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Models\Evest;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\ResourceController;
use Modules\UserPanel\Services\ResourceService;

class DashboardController extends ResourceController
{
    public $icon = 'fa fa-dashboard';
    public $model = Evest::class;
    public $routeName = 'dashboard';

    /**
     * Make the resource instance
     */
    protected function makeResource(): ResourceService
    {
        return (new ResourceService(Evest::class, 'dashboard'))
            ->title('Dashboard Management')
            ->description('Manage dashboard records with full CRUD operations')

            // Define fields
            ->text('title')
                ->required()
                ->searchable()
                ->sortable()
                ->rules(['max:255'])

            ->textarea('desc')
                ->required()
                ->searchable()
                ->rules(['max:1000'])

            ->select('uploader_id')
                ->required()
                ->options(function() {
                    return User::all()->pluck('name', 'id')->toArray();
                })
                ->filterable([
                    'type' => 'select',
                    'options' => function() {
                        return User::all()->pluck('name', 'id')->toArray();
                    }
                ])
                ->rules(['exists:users,id'])

            ->text('path')
                ->searchable()
                ->rules(['nullable', 'max:500'])

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

    /**
     * Override the createForm method for custom form layout if needed
     */
    public function createForm($mode = 'create')
    {
        // This method is now handled by the ResourceService
        // You can override it here if you need custom form logic
        return parent::createForm($mode);
    }

    /**
     * Override the dataSetView method for custom grid layout if needed
     */
    public function dataSetView()
    {
        // This method is now handled by the ResourceService
        // You can override it here if you need custom grid logic
        return $this->resource->index();
    }
}
