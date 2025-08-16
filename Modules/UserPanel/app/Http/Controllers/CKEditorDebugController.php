<?php

namespace Modules\UserPanel\Http\Controllers;

use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\BaseController;
use Modules\UserPanel\Services\ResourceService;

class CKEditorDebugController extends BaseController
{
    public $icon = 'fa fa-bug';
    public $showInSidebar = true;
    public $model = null;

    public function create()
    {
        $resource = new ResourceService(null, 'ckeditor-debug');
        
        $resource->title('CKEditor Debug')
            ->description('Debugging CKEditor integration')
            ->enableTabs()
            ->tab('test', 'Test', 'fa fa-test')
                ->richText('content')
                ->end();

        // Debug: Let's see what's in the resource
        $this->form->clear();
        $resource->buildForm($this->form);
        
        // Debug: Check if the form has the right fields
        $formContent = $this->form->renderFormContent();
        
        return view('userpanel::ckeditor-debug', [
            'form' => $this->form,
            'debug' => [
                'resource_fields' => $resource->getFields(),
                'form_content' => $formContent
            ]
        ]);
    }

    public function store(Request $request)
    {
        return redirect()->back()->with('success', 'Debug form submitted');
    }
}
