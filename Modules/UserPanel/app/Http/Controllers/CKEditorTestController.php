<?php

namespace Modules\UserPanel\Http\Controllers;

use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\BaseController;
use Modules\UserPanel\Services\ResourceService;

class CKEditorTestController extends BaseController
{
    public $icon = 'fa fa-test';
    public $showInSidebar = true;
    public $model = null;

    public function create()
    {
        $resource = new ResourceService(null, 'ckeditor-test');
        
        $resource->title('CKEditor Test')
            ->description('Testing CKEditor integration with ResourceService')
            ->enableTabs()
            ->tab('general', 'General Information', 'fa fa-info-circle')
                ->text('title')->searchable()->sortable()
                ->textarea('description')->ckeditor(true)
                ->end()
            ->tab('content', 'Rich Content', 'fa fa-edit')
                ->richText('content')->height(300)
                ->end();

        $this->form->clear();
        $resource->buildForm($this->form);
        
        return view('userpanel::ckeditor-test', [
            'form' => $this->form
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
        ]);

        return redirect()->back()->with('success', 'Form submitted successfully! Content length: ' . strlen($request->content));
    }
}
