<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\BaseController;
use Modules\UserPanel\Services\FormService;
use Modules\UserPanel\Services\LayoutService;

class SimpleLayoutController extends BaseController
{
    // Set to true to show in sidebar
    public $showInSidebar = true;

    public function index()
    {
        // Create layout
        $layout = new LayoutService();

        // Create form
        $form = new FormService();

        // Simple layout with sections and fields

        // Row with two columns
        $row = $layout->row();
        $row->column(6)
            ->addField(
                $form->text()
                    ->name('phone')
                    ->label('Phone Number')
                    ->placeholder('Enter your phone')
                    ->required()
            );
        $row->column(6)
            ->addField(
                $form->text()
                    ->name('website')
                    ->label('Website')
                    ->placeholder('Enter your website')
            );





        return view('userpanel::simple-layout', [
            'layout' => $layout->render()
        ]);
    }
}
