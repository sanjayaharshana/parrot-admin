<?php

namespace Modules\Products\Http\Controllers;

use Modules\Products\Models\Categories;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\ResourceController;
use Modules\UserPanel\Services\ResourceService;


class CategoriesController extends ResourceController
{
    public $icon = 'fa fa-cube';
    public $model = Categories::class;
    public $routeName = 'categories';
    public $parentMenu = 'Products';


    protected function makeResource(): ResourceService
    {
        return (new ResourceService(Categories::class, 'categories'))
            ->title('Categories Management')
            ->description('Manage categories records')
            ->enableTabs()
            ->tab('general', 'General', 'fa fa-info-circle')
                ->text('name')->searchable()->sortable()
                ->textarea('description')
                ->text('slug')->searchable()->sortable()
                ->file('image')->accept('image/*')
                ->text('thumbnail')->searchable()->sortable()
                ->text('is_active')->searchable()->sortable()
                ->text('sort_order')->searchable()->sortable()
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
        $dataView = new \Modules\UserPanel\Services\DataViewService(new Categories());

        $dataView->title('Categories Management')
            ->description('Manage categories records')
            ->routePrefix('categories')
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

        $dataView->column('image', 'Image')
            ->sortable()
            ->searchable();

        $dataView->column('thumbnail', 'Thumbnail')
            ->sortable()
            ->searchable();

        $dataView->column('is_active', 'Is Active')
            ->sortable()
            ->searchable();

        $dataView->column('sort_order', 'Sort Order')
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
        $dataView->createButton(route('categories.create'), 'Create New');

        return $dataView;
    }
}
