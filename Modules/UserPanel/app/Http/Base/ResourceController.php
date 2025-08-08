<?php

namespace Modules\UserPanel\Http\Base;

use Illuminate\Http\Request;
use Modules\UserPanel\Services\ResourceService;

abstract class ResourceController extends BaseController
{
    protected ResourceService $resource;
    protected string $modelClass;
    protected string $resourceName;

    public function __construct()
    {
        parent::__construct();
        $this->resource = $this->makeResource();
    }

    /**
     * Make the resource instance
     */
    abstract protected function makeResource(): ResourceService;

    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataView = $this->dataView();
        $gridContent = $dataView->render();

        // Get title and description from the data view
        $data = $dataView->getData();

        return view('userpanel::index', [
            'grid' => $gridContent,
            'title' => $data['title'] ?? 'Ship Management',
            'description' => $data['description'] ?? 'Manage ships with full CRUD operations'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = $this->resource->create();

        return view('userpanel::create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = $this->resource->store($request);

        if (!$result['success']) {
            return redirect()->back()
                ->withErrors($result['errors'])
                ->withInput();
        }

        try {
            return redirect()->route($this->resource->getRoutePrefix() . '.index')
                ->with('success', 'Record created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating record: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $model = $this->resource->getModel()::findOrFail($id);

        return view('userpanel::show', [
            'model' => $model,
            'title' => "View {$this->resource->getTitle()}",
            'description' => "Details for {$this->resource->getTitle()} record"
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = $this->resource->edit($id);

        return view('userpanel::edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $result = $this->resource->update($request, $id);

        if (!$result['success']) {
            return redirect()->back()
                ->withErrors($result['errors'])
                ->withInput();
        }

        try {
            return redirect()->route($this->resource->getRoutePrefix() . '.index')
                ->with('success', 'Record updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating record: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $model = $this->resource->getModel()::findOrFail($id);
            $model->delete();

            return redirect()->route($this->resource->getRoutePrefix() . '.index')
                ->with('success', 'Record deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->route($this->resource->getRoutePrefix() . '.index')
                ->with('error', 'Error deleting record: ' . $e->getMessage());
        }
    }

    /**
     * Get the resource service
     */
    public function getResource(): ResourceService
    {
        return $this->resource;
    }
}
