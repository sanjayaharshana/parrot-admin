<?php

namespace Modules\UserPanel\Http\Controllers;

use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\BaseController;
use Modules\UserPanel\Services\Form\FormService;
use Modules\UserPanel\Services\LayoutService;

class CKEditorDemoController extends BaseController
{
    public $icon = 'fa fa-edit';
    public $showInSidebar = true;
    public $model = null; // No model for demo

    public function create()
    {
        $this->form->routeForStore('ckeditor-demo');
        
        $this->createForm();
        return view('userpanel::ckeditor-demo', [
            'form' => $this->form
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
        ]);

        // In a real application, you would save to database here
        return redirect()->back()->with('success', 'Content saved successfully! Content length: ' . strlen($request->content));
    }

    protected function createForm()
    {
        $layout = $this->layoutService;
        $layout->setFormService($this->form);
        
        // Basic information section
        $layout->section('Basic Information', 'Enter the basic details for your content.')
            ->addField(
                $this->form->text()
                    ->name('title')
                    ->label('Title')
                    ->placeholder('Enter content title')
                    ->required()
            )
            ->addField(
                $this->form->textarea()
                    ->name('excerpt')
                    ->label('Excerpt')
                    ->placeholder('Brief description of the content')
            );

        // Rich content section with CKEditor
        $layout->section('Rich Content', 'Use the rich text editor below to create formatted content.')
            ->addField(
                $this->form->richText()
                    ->name('content')
                    ->label('Content')
                    ->placeholder('Start writing your content here...')
                    ->required()
            );

        // Alternative way to enable CKEditor on existing textarea
        $layout->section('Alternative CKEditor Usage', 'You can also enable CKEditor on existing textarea fields.')
            ->addField(
                $this->form->textarea()
                    ->name('additional_content')
                    ->label('Additional Content')
                    ->placeholder('This textarea will also use CKEditor')
                    ->ckeditor(true) // Enable CKEditor manually
            );

        return $layout->render();
    }
}
