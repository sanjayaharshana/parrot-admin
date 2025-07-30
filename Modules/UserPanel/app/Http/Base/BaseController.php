<?php

namespace Modules\UserPanel\Http\Base;

use App\Http\Controllers\Controller;
use App\Models\Evest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Modules\PluginManager\Services\PluginService;
use Modules\UserPanel\Services\DataViewService;
use Modules\UserPanel\Services\FormService;

class BaseController extends Controller
{

    /**
     * Display a listing of the resource.
     */

    public $icon = 'fa fa-users';

    // Set to false to exclude from sidebar, true by default
    public $showInSidebar = true;

    // Layout service for creating page layouts
    protected $layoutService;

    // Form service for creating forms
    protected $form;
    protected $type;
    protected $dataSet;
    protected $model;

    function __construct()
    {
        $this->layoutService = new \Modules\UserPanel\Services\LayoutService();
        $this->form = new \Modules\UserPanel\Services\FormService();
    }

    public function dataSetView()
    {
        return null;
    }

    public function index()
    {
        $gridDetails = $this::dataSetView();
        return view('userpanel::index', [
            'grid' => $gridDetails,
            'title' => 'Users Data Grid'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $layout = $this->createForm();
        return view('userpanel::create',[
            'layout' => $layout
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {

    }

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
