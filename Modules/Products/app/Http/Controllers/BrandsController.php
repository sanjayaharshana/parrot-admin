<?php

namespace Modules\Products\Http\Controllers;

use Modules\Products\Models\Brands;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\ResourceController;
use Modules\UserPanel\Services\ResourceService;

class BrandsController extends ResourceController
{
    public $icon = 'fa fa-cube';
    public $model = Brands::class;
    public $routeName = 'brands';
    public $parentMenu = 'Products';

    protected function makeResource(): ResourceService
    {
        return (new ResourceService(Brands::class, 'brands'))
            ->title('Brands Management')
            ->description('Manage brands records')
            ->enableTabs()
            ->tab('general', 'General', 'fa fa-info-circle')
                ->text('name')->searchable()->sortable()
                ->textarea('description')
                ->text('slug')->searchable()->sortable()
                ->text('logo')->searchable()->sortable()
                ->text('website')->searchable()->sortable()
                ->email('contact_email')->searchable()->sortable()
            ->end()
            ->actions([
                'view' => [ 'label' => 'View', 'icon' => 'fa fa-eye', 'route' => 'show' ],
                'edit' => [ 'label' => 'Edit', 'icon' => 'fa fa-edit', 'route' => 'edit' ],
                'delete' => [ 'label' => 'Delete', 'icon' => 'fa fa-trash', 'route' => 'destroy', 'method' => 'DELETE', 'confirm' => true ],
            ])
            ->bulkActions([
                'delete' => [ 'label' => 'Delete Selected', 'icon' => 'fa fa-trash', 'confirm' => true ],
            ]);
    }

    public function dataView()
    {
        $dataView = new \Modules\UserPanel\Services\DataViewService(new Brands());

        $dataView->title('Brands Management')
            ->description('Manage brands records')
            ->routePrefix('brands')
            ->perPage(15)
            ->defaultSort('id', 'desc')
            ->pagination(true)
            ->search(true);

        // ID column
        $dataView->id('ID')->sortable();

        $dataView->column('name', 'Name')
            ->sortable()
            ->searchable();

        $dataView->column('description', 'Description')
            ->sortable()
            ->searchable();

        $dataView->column('slug', 'Slug')
            ->sortable()
            ->searchable();

        $dataView->column('logo', 'Logo')
            ->sortable()
            ->searchable();

        $dataView->column('website', 'Website')
            ->sortable()
            ->searchable();

        $dataView->column('contact_email', 'Contact Email')
            ->sortable()
            ->searchable();

        // Actions
        $dataView->actions([
            'view' => [ 'label' => 'View', 'icon' => 'fa fa-eye', 'route' => 'show' ],
            'edit' => [ 'label' => 'Edit', 'icon' => 'fa fa-edit', 'route' => 'edit' ],
            'delete' => [ 'label' => 'Delete', 'icon' => 'fa fa-trash', 'route' => 'destroy', 'method' => 'DELETE', 'confirm' => true ],
        ]);

        // Bulk actions
        $dataView->bulkActions([
            'delete' => [ 'label' => 'Delete Selected', 'icon' => 'fa fa-trash', 'confirm' => true ],
        ]);

        // Create button
        $dataView->createButton(route('brands.create'), 'Create New');

        return $dataView;
    }
}
