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
        // Set the form route for store action
        $this->form->routeForStore($this->getRouteName());

        $this->createForm('create');
        return view('userpanel::create', [
            'form' => $this->form
        ]);
    }



    /**
     * Get the store route for the current resource
     */
    protected function getStoreRoute()
    {
        $routeName = $this->getRouteName() . '.store';
        return route($routeName);
    }

    /**
     * Get the update route for the current resource
     */
    protected function getUpdateRoute($id)
    {
        $routeName = $this->getRouteName() . '.update';
        return route($routeName, $id);
    }

    /**
     * Get the route name prefix for the current resource
     * Override this in child controllers if needed
     */
    protected function getRouteName()
    {
        // If routeName property is set, use it
        if (isset($this->routeName)) {
            return $this->routeName;
        }

        // Otherwise, extract route name from the current controller class name
        $className = class_basename($this);
        $resourceName = str_replace('Controller', '', $className);
        return strtolower($resourceName) . 's';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Handle the form submission with validation
        $result = $this->form->handle($request);

        if (!$result['success']) {
            return redirect()->back()
                ->withErrors($result['errors'])
                ->withInput();
        }

        try {
            return redirect()->route($this->getRouteName() . '.index')
                ->with('success', 'Record created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating record: ' . $e->getMessage())
                ->withInput();
        }
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
        // Set the form route for update action
        $this->form->routeForUpdate($this->getRouteName(), $id);

        return view('userpanel::edit', [
            'form' => $this->form
        ]);
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
