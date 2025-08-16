<?php

namespace Modules\UserPanel\Http\Controllers;

use Modules\UserPanel\Models\TVShows;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\ResourceController;
use Modules\UserPanel\Services\ResourceService;

class TVShowsController extends ResourceController
{
    public $icon = 'fa fa-cube';
    public $model = TVShows::class;
    public $routeName = 't-v-shows';

    protected function makeResource(): ResourceService
    {
        return (new ResourceService(TVShows::class, 't-v-shows'))
            ->title('TVShows Management')
            ->description('Manage t-v-shows records')
            ->enableTabs()
            ->tab('general', 'General', 'fa fa-info-circle')
                ->text('video')->searchable()->sortable()
                ->text('title')->searchable()->sortable()
                ->text('slug')->searchable()->sortable()
                ->richText('description')
                ->text('thumbnail')->searchable()->sortable()
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
        $dataView = new \Modules\UserPanel\Services\DataViewService(new TVShows());

        $dataView->title('TVShows Management')
            ->description('Manage t-v-shows records')
            ->routePrefix('t-v-shows')
            ->perPage(15)
            ->defaultSort('id', 'desc')
            ->pagination(true)
            ->search(true);

        // ID column
        $dataView->id('ID')->sortable();

        $dataView->column('video', 'Video')
            ->sortable()
            ->searchable();

        $dataView->column('title', 'Title')
            ->sortable()
            ->searchable();

        $dataView->column('slug', 'Slug')
            ->sortable()
            ->searchable();

        $dataView->column('description', 'Description')
            ->sortable()
            ->searchable();

        $dataView->column('thumbnail', 'Thumbnail')
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
        $dataView->createButton(route('t-v-shows.create'), 'Create New');

        return $dataView;
    }
}