<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\BaseController;

class DashboardController extends BaseController
{
    public $icon = 'fa fa-dashboard';

    public function page()
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


}
