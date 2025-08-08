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
    public $model = Evest::class;
    public $routeName = 'dashboard';

    public function createForm($mode = 'create')
    {
        // Clear any existing layout items and fields to prevent duplication
        $this->form->clear();

        // Add header information using FormService's layout system
        $headerRow = $this->form->row();
        $headerRow->column(6, function ($form, $column) {
            $column->addHtml('<h3 class="text-lg font-semibold">Create New Record</h3>');
            $column->addHtml('<p class="text-sm text-gray-600">Fill in the details below to create a new record.</p>');
        });

        // Add the header row to the form's layout
        $this->form->addLayoutItem($headerRow);

        // Add form fields using FormService's layout system
        $layoutRow = $this->form->row();
        $layoutRow->column(6, function ($form, $column) {
            $column->addField(
                $form->text()
                    ->name('title')
                    ->label('Title')
                    ->placeholder('Enter title')
                    ->required() // Automatically adds 'required' and 'string' rules
            );
            $column->addHtml('<br><p class="text-muted">This is a custom HTML content block that can be used to display additional information or instructions.</p><br>');
            $column->addField(
                $form->textarea()
                    ->name('desc')
                    ->label('Description')
                    ->placeholder('Enter description')
                    ->required() // Automatically adds 'required' and 'string' rules
            );
        });
        $layoutRow->column(6, function ($form, $column) {
            $column->addField(
                $form->select()
                    ->name('uploader_id')
                    ->label('Uploader')
                    ->options(function () {
                        return User::all()->pluck('name', 'id')->toArray();
                    })
                    ->required() // Automatically adds 'required' rule
            );

            $column->addField(
                $form->text()
                    ->name('path')
                    ->label('Path')
                    ->placeholder('Enter file path')
            );
        });

        // Add the layout row to the form's layout
        $this->form->addLayoutItem($layoutRow);
    }

    public function dataSetView()
    {
        $grid = new DataViewService(new Evest());

        $grid->title('User Management');
        $grid->description('Manage your users efficiently with this comprehensive grid view. You can sort, filter, and search through user data seamlessly.');

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

        $grid->createButton(url('dashboard/create'),'Create', 'fa fa-plus', 'btn-primary');
        // Configure grid settings
        $grid->perPage(10)
            ->defaultSort('created_at', 'desc')
            ->search(false)
            ->pagination(true);

        return $grid->render();
    }

    /**
     * Show the form for creating a new resource
     */


    /**
     * Display the specified resource
     */
    public function show($id)
    {
        $model = $this->model::findOrFail($id);

        return view('userpanel::show', [
            'model' => $model,
            'title' => 'Dashboard Details'
        ]);
    }

    /**
     * Show the form for editing the specified resource
     */
    public function edit($id)
    {
        $model = $this->model::findOrFail($id);

        // Bind the model to the form service
        $this->form->bindModel($model);

        // Set the form route for update action
        $this->form->routeForUpdate($this->getRouteName(), $id);

        $this->createForm('edit');

        return view('userpanel::edit', [
            'form' => $this->form,
            'model' => $model
        ]);
    }

    /**
     * Store a newly created resource
     */


    /**
     * Update the specified resource
     */
    public function update(Request $request, $id)
    {
        $model = $this->model::findOrFail($id);

        // Bind the model to the form service
        $this->form->bindModel($model);

        // Set the form route for update action
        $this->form->routeForUpdate($this->getRouteName(), $id);

        // Set up the form with validation rules
        $this->createForm('edit');

        // Handle the form submission with validation
        $result = $this->form->handle($request);

        if (!$result['success']) {
            return redirect()->back()
                ->withErrors($result['errors'])
                ->withInput();
        }

        try {
            return redirect()->route($this->getRouteName() . '.index')
                ->with('success', 'Record updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating record: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource
     */
    public function destroy($id)
    {
        try {
            $model = $this->model::findOrFail($id);
            $model->delete();

            return redirect()->route($this->getRouteName() . '.index')
                ->with('success', 'Record deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->route($this->getRouteName() . '.index')
                ->with('error', 'Error deleting record: ' . $e->getMessage());
        }
    }

}
