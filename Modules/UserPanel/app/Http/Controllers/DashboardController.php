<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Evest;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\BaseController;
use Modules\UserPanel\Services\DataViewService;

class DashboardController extends BaseController
{
    public $icon = 'fa fa-dashboard';

    public function createForm()
    {
        $layout = $this->layoutService;
        $layout->setFormService($this->form);
        $stateRow  = $layout->row();
        $stateRow->column(6, function ($form, $column) use ($layout) {
            $column->addView('userpanel::components.custom-stats', [
                'stats' => [
                    ['label' => 'Total Users', 'value' => '1,234', 'icon' => 'users'],
                    ['label' => 'Active Projects', 'value' => '56', 'icon' => 'folder'],
                    ['label' => 'Completed Tasks', 'value' => '789', 'icon' => 'check']
                ]
            ]);
        });
        $stateRow->column(6, function ($form, $column) use ($layout) {
            $column->addView('userpanel::components.custom-stats', [
                'stats' => [
                    ['label' => 'Total Users', 'value' => '1,234', 'icon' => 'users'],
                    ['label' => 'Active Projects', 'value' => '56', 'icon' => 'folder'],
                    ['label' => 'Completed Tasks', 'value' => '789', 'icon' => 'check']
                ]
            ]);
        });

        $layoutRow = $layout->row();
        $layoutRow->column(6,function ($form, $column) use ($layout) {

            // How to make the first column occupy 1/2 of the page width

            $column->addField(
                $form->text()
                    ->name('title')
                    ->label('Title')
                    ->placeholder('Enter title')
                    ->required()
            );
            $column->addHtml('<br><p class="text-muted">This is a custom HTML content block that can be used to display additional information or instructions.</p><br>');
            $column->addField(
                $form->textarea()
                    ->name('desc')
                    ->label('Description')
                    ->placeholder('Enter description')
                    ->required()
            );
        });
        $layoutRow->column(6, function ($form, $column) use ($layout) {
            $column->addField(
                $form->select()
                    ->name('uploader_id')
                    ->label('Uploader')
                    ->options(function () {
                        return User::all()->pluck('name', 'id')->toArray();
                    })
                    ->required()
            );

            $column->addField(
                $form->text()
                    ->name('path')
                    ->label('Path')
                    ->placeholder('Enter file path')
            );
        });
        return $layout->render();
    }

    public function dataSetView()
    {
        $grid = new DataViewService(new Evest());

        // The first column displays the id field and sets the column as a sortable column
        $grid->id('ID')->sortable();

        // The second column shows the name field
        $grid->column('name', 'Full Name')->sortable();

        // The third column shows the email field
        $grid->column('email', 'Email Address')->sortable();

        // The fourth column shows the created_at field with custom formatting
        $grid->column('created_at', 'Joined Date')->display(function($value) {
            return $value ? date('M d, Y', strtotime($value)) : 'N/A';
        });

        // The fifth column shows the email_verified_at field with status indicator
        $grid->column('email_verified_at', 'Status')->display(function($value) {
            if ($value) {
                return '<span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Verified</span>';
            }
            return '<span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Unverified</span>';
        });

        $grid->addTextFilter('name', 'Name');
        $grid->addTextFilter('email', 'Email');
        $grid->addDateRangeFilter('created_at', 'Member Since');
        $grid->addFilter('email_verified_at', 'Email Status', [
            'verified' => 'Verified',
            'unverified' => 'Not Verified'
        ], 'select');

        // Configure grid settings
        $grid->perPage(10)
            ->defaultSort('created_at', 'desc')
            ->search(false)
            ->pagination(true);

        return $grid->render();
    }


}
