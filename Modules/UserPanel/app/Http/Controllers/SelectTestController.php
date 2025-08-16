<?php

namespace Modules\UserPanel\Http\Controllers;

use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\BaseController;
use Modules\UserPanel\Services\Form\FormService;

class SelectTestController extends BaseController
{
    public $icon = 'fa fa-list';
    public $showInSidebar = true;
    public $model = null;

    public function create()
    {
        $this->form->routeForStore('select-test');
        
        // Test select field with options, searchable, and label
        $this->form->addField(
            $this->form->select('category_id')
                ->options([
                    1 => 'Technology',
                    2 => 'Science',
                    3 => 'Arts',
                    4 => 'Sports',
                    5 => 'Business'
                ])
                ->searchable()
                ->label('Category')
                ->required()
        );
        
        // Test select field with array of objects (like Eloquent models)
        $this->form->addField(
            $this->form->select('user_id')
                ->options([
                    ['id' => 1, 'name' => 'John Doe'],
                    ['id' => 2, 'name' => 'Jane Smith'],
                    ['id' => 3, 'name' => 'Bob Johnson']
                ])
                ->searchable()
                ->label('User')
        );
        
        // Test select field with simple array
        $this->form->addField(
            $this->form->select('status')
                ->options(['active' => 'Active', 'inactive' => 'Inactive', 'pending' => 'Pending'])
                ->label('Status')
        );
        
        return view('userpanel::select-test', [
            'form' => $this->form
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer',
            'user_id' => 'nullable|integer',
            'status' => 'nullable|string',
        ]);

        return redirect()->back()->with('success', 'Form submitted successfully!');
    }
}

