<?php

namespace Modules\UserPanel\Http\Base;

use App\Http\Controllers\Controller;


class PageController extends Controller
{
    function __construct()
    {
        $this->layoutService = new \Modules\UserPanel\Services\LayoutService();
        $this->form = new \Modules\UserPanel\Services\FormService();
    }

    public function layout()
    {

    }

    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        // Allow child controllers to build layout using the shared services
        if (method_exists($this->layoutService, 'setFormService')) {
            $this->layoutService->setFormService($this->form);
        }

        // Hook for child controller to define layout
        if (method_exists($this, 'layout')) {
            $this->layout();
        }

        // Derive a sensible title if none was provided by the child
        $title = property_exists($this, 'title') && $this->title
            ? $this->title
            : trim(str_replace('Controller', '', class_basename(static::class)));

        return view('userpanel::custom-page', [
            'title' => $title,
            'layout' => $this->layoutService->render(),
        ]);
    }

}
