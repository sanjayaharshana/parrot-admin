<?php

namespace Modules\UserPanel\Http\Controllers;

use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\BaseController;
use Modules\UserPanel\Services\Form\FormService;

class SwitchTestController extends BaseController
{
    public $icon = 'fa fa-toggle-on';
    public $showInSidebar = true;
    public $model = null;

    public function create()
    {
        $this->form->routeForStore('switch-test');
        
        // Test switch field with searchable and sortable
        $this->form->addField(
            $this->form->switch('is_active')
                ->label('Active Status')
                ->searchable()
                ->sortable()
        );
        
        // Test switch field with default value
        $this->form->addField(
            $this->form->switch('is_featured')
                ->label('Featured Item')
                ->value(true)
        );
        
        // Test switch field with custom label
        $this->form->addField(
            $this->form->switch('is_public')
                ->label('Public Visibility')
                ->searchable()
        );
        
        // Test switch field with required
        $this->form->addField(
            $this->form->switch('is_verified')
                ->label('Verification Status')
                ->required()
        );
        
        return view('userpanel::switch-test', [
            'form' => $this->form
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_public' => 'nullable|boolean',
            'is_verified' => 'required|boolean',
        ]);

        return redirect()->back()->with('success', 'Form submitted successfully!');
    }
}
