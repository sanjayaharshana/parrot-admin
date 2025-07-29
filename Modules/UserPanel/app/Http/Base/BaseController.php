<?php

namespace Modules\UserPanel\Http\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Modules\PluginManager\Services\PluginService;
use Modules\UserPanel\Services\FormService;

class BaseController extends Controller
{

    /**
     * Display a listing of the resource.
     */

    public $icon = 'fa fa-users';
    
    // Set to false to exclude from sidebar, true by default
    public $showInSidebar = true;


    public function index()
    {
        $formService = new FormService();
        $formService->text()->name('text_field_name')->value('value');
        $formService->textarea()->name('textarea_name')->value('some text');
        $formService->text()->name('firstName')->value('John');

        return view('userpanel::index',[
            'form' => $formService->renderForm()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('userpanel::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('userpanel::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('userpanel::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource in storage.
     */
    public function destroy($id) {}
}
