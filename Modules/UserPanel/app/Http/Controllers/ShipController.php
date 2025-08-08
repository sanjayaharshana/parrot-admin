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
     * Make the resource instance
     */
    protected function makeResource(): ResourceService
    {
        return (new ResourceService(Ship::class, 'ships'))
            ->title('Ship Management')
            ->description('Manage ships with full CRUD operations')
            
            // Define fields
            ->text('name')
                ->required()
                ->searchable()
                ->sortable()
                ->rules(['max:255'])
            
            ->textarea('address')
                ->required()
                ->searchable()
                ->rules(['max:1000'])
            
            ->text('ship')
                ->required()
                ->searchable()
                ->sortable()
                ->rules(['max:255'])
            
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
} 