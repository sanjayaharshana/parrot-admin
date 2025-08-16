<?php

namespace Modules\Documentation\Http\Controllers;

use Modules\Documentation\Models\DocumentationCategory;
use Modules\UserPanel\Services\ResourceService;
use Modules\UserPanel\Http\Base\ResourceController;


class DocumentationCategoryController extends ResourceController
{
    public $icon = 'fa fa-cube';
    public $model = DocumentationCategory::class;
    public $routeName = 'documentation-categories';
    public $parentMenu = 'Documentation';

    protected function makeResource(): ResourceService
    {
        return (new ResourceService(DocumentationCategory::class, 'documentation-categories'))
            ->title('DocumentationCategory Management')
            ->description('Manage documentation-categories records')
            ->enableTabs()
            ->tab('general', 'General', 'fa fa-info-circle')
                ->text('name')->searchable()->sortable()
                ->text('slug')->searchable()->sortable()
                ->textarea('description')
                ->text('icon')->searchable()->sortable()
                ->text('color')->searchable()->sortable()
                ->text('sort_order')->searchable()->sortable()
                ->text('is_active')->searchable()->sortable()
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
        $dataView = new \Modules\UserPanel\Services\DataViewService(new DocumentationCategory());

        $dataView->title('DocumentationCategory Management')
            ->description('Manage documentation-categories records')
            ->routePrefix('documentation-categories')
            ->perPage(15)
            ->defaultSort('id', 'desc')
            ->pagination(true)
            ->search(true);

        // ID column
        $dataView->id('ID')->sortable();

        $dataView->column('name', 'Name')
            ->sortable()
            ->searchable();

        $dataView->column('slug', 'Slug')
            ->sortable()
            ->searchable();

        $dataView->column('description', 'Description')
            ->sortable()
            ->searchable();

        $dataView->column('icon', 'Icon')
            ->sortable()
            ->searchable();

        $dataView->column('color', 'Color')
            ->sortable()
            ->searchable();

        $dataView->column('sort_order', 'Sort Order')
            ->sortable()
            ->searchable();

        $dataView->column('is_active', 'Is Active')
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
        $dataView->createButton(route('documentation-categories.create'), 'Create New');

        return $dataView;
    }
}
