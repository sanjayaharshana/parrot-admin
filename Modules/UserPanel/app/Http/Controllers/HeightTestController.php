<?php

namespace Modules\UserPanel\Http\Controllers;

use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\BaseController;
use Modules\UserPanel\Services\Form\FormService;

class HeightTestController extends BaseController
{
    public $icon = 'fa fa-test';
    public $showInSidebar = true;
    public $model = null;

    public function create()
    {
        $this->form->routeForStore('height-test');
        
        // Test different heights
        $this->form->addField(
            $this->form->text()
                ->name('title')
                ->label('Title')
                ->required()
        );
        
        $this->form->addField(
            $this->form->richText()
                ->name('content')
                ->label('Content (Height: 200px)')
                ->height(200)
                ->required()
        );
        
        $this->form->addField(
            $this->form->richText()
                ->name('content2')
                ->label('Content 2 (Height: 400px)')
                ->height(400)
                ->required()
        );
        
        $this->form->addField(
            $this->form->textarea()
                ->name('description')
                ->label('Description (Height: 300px)')
                ->ckeditor(true)
                ->height(300)
        );
        
        return view('userpanel::height-test', [
            'form' => $this->form
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'content2' => 'required|string',
            'description' => 'nullable|string',
        ]);

        return redirect()->back()->with('success', 'Form submitted successfully!');
    }
}
