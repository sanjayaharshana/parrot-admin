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
            
            // Basic Information Section
            ->text('name')
                ->required()
                ->searchable()
                ->sortable()
                ->rules(['max:255'])
                ->section('Basic Information')
            
            ->text('ship')
                ->required()
                ->searchable()
                ->sortable()
                ->rules(['max:255'])
                ->section('Basic Information')
            
            // Address Section
            ->textarea('address')
                ->required()
                ->searchable()
                ->rules(['max:1000'])
                ->section('Location Details')
                ->width(12) // Full width for textarea
            
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