<?php

namespace Modules\Documentation\Http\Controllers;

use Modules\Documentation\Models\DocumentationPage;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\ResourceController;
use Modules\UserPanel\Services\ResourceService;


class DocumentationPageController extends ResourceController
{
    public $icon = 'fa fa-cube';
    public $model = DocumentationPage::class;
    public $routeName = 'documentation-pages';
    public $parentMenu = 'Dashboard';



    protected function makeResource(): \Modules\UserPanel\Services\ResourceService
    {

        return (new \Modules\UserPanel\Services\ResourceService(\Modules\Documentation\Models\DocumentationPage::class, 'documentation-pages'))
            ->title('DocumentationPage Management')
            ->description('Manage documentation-pages records')
            ->enableTabs()
            ->tab('general', 'General', 'fa fa-info-circle')
                ->text('category_id')->searchable()->sortable()
                ->text('title')->searchable()->sortable()
                ->text('slug')->searchable()->sortable()
                ->text('excerpt')->searchable()->sortable()
                ->richText('content')->height(400)
                ->text('meta_title')->searchable()->sortable()
                ->textarea('meta_description')
                ->text('meta_keywords')->searchable()->sortable()
                ->text('sort_order')->searchable()->sortable()
                ->text('is_active')->searchable()->sortable()
                ->text('is_featured')->searchable()->sortable()
                ->text('published_at')->searchable()->sortable()
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
        $dataView = new \Modules\UserPanel\Services\DataViewService(new \Modules\Documentation\Models\DocumentationPage());

        $dataView->title('DocumentationPage Management')
            ->description('Manage documentation-pages records')
            ->routePrefix('documentation-pages')
            ->perPage(15)
            ->defaultSort('id', 'desc')
            ->pagination(true)
            ->search(true);

        // ID column
        $dataView->id('ID')->sortable();

        $dataView->column('category_id', 'Category Id')
            ->sortable()
            ->searchable();

        $dataView->column('title', 'Title')
            ->sortable()
            ->searchable();

        $dataView->column('slug', 'Slug')
            ->sortable()
            ->searchable();

        $dataView->column('is_active', 'Is Active')
            ->sortable()
            ->searchable();

        $dataView->column('is_featured', 'Is Featured')
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
        $dataView->createButton(route('documentation-pages.create'), 'Create New');

        return $dataView;
    }
}
