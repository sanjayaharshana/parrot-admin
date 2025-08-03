<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\BaseController;
use Modules\UserPanel\Services\DataViewService;
use App\Models\User;

class SimpleLayoutController extends BaseController
{
    // Set to true to show in sidebar
    public $showInSidebar = true;
    public $model = User::class;

    public function index()
    {
        // Create a simple grid with search and filters using only existing User fields
        $grid = new DataViewService(new User);

        // ID column
        $grid->id('ID')->sortable();

        // Name column - make it searchable
        $grid->column('name', 'Name')->searchable()->sortable();

        // Email column - make it searchable
        $grid->column('email', 'Email')->searchable()->sortable();

        // Created date - add date range filter
        $grid->column('created_at', 'Created At')->sortable()->display(function($value) {
            return date('M d, Y', strtotime($value));
        });

        // Email verification status
        $grid->column('email_verified_at', 'Email Verified')->display(function($value) {
            if ($value) {
                return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Verified</span>';
            } else {
                return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Not Verified</span>';
            }
        });

        // Add filters for existing fields
        $grid->addTextFilter('name', 'Name');
        $grid->addTextFilter('email', 'Email');
        $grid->addDateRangeFilter('created_at', 'Created Date');
        $grid->addFilter('email_verified_at', 'Email Status', [
            'verified' => 'Verified',
            'unverified' => 'Not Verified'
        ], 'select');

        // Configure settings
        $grid->perPage(10)
            ->defaultSort('created_at', 'desc')
            ->search(true)
            ->filters(true)
            ->pagination(true)
            ->title('User Management')
            ->description('Manage and view all registered users in the system');

        return view('userpanel::index', [
            'grid' => $grid
        ]);
    }
}
