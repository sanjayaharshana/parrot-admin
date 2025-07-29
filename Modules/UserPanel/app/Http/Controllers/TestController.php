<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\BaseController;
use Modules\UserPanel\Services\FormService;

class TestController extends BaseController
{
    // Set to false to exclude from sidebar
    public $showInSidebar = true;

    public function index()
    {
        $form = new FormService();
        $form->text()->name('text_field_name')
            ->value('value');
        return view('userpanel::index', [
            'form' => $form->renderForm()
        ]);
    }

}
