<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\BaseController;
use Modules\UserPanel\Services\DataViewService;
use App\Models\User;
use App\Models\Product;

class TestController extends BaseController
{
    // Set to true to show in sidebar
    public $showInSidebar = true;
    public $model = User::class;

    public function index()
    {
        return view('userpanel::test', [
            'title' => 'Grid Examples with Search & Filters'
        ]);
    }

    public function debug()
    {
        // Simple debug grid with minimal features
        $grid = new DataViewService(new User);

        // Only basic columns
        $grid->id('ID');
        $grid->column('name', 'Name');
        $grid->column('email', 'Email');
        $grid->column('created_at', 'Created')->display(function($value) {
            return date('M d, Y', strtotime($value));
        });

        // Only basic search
        $grid->search(true)->filters(false)->pagination(true);

        return view('userpanel::data-view', [
            'grid' => $grid,
            'title' => 'Debug Grid - Basic Functionality'
        ]);
    }

    public function usersWithSearch()
    {
        // Create a grid with enhanced search and filters using only existing User fields
        $grid = new DataViewService(new User);

        // ID column
        $grid->id('ID')->sortable();

        // Name with avatar - searchable
        $grid->column('name', 'User')->searchable()->sortable()->display(function($value, $user) {
            $avatar = $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($value) . '&color=7C3AED&background=EBF4FF';
            return "
                <div class='flex items-center'>
                    <img class='h-8 w-8 rounded-full mr-3' src='{$avatar}' alt='{$value}'>
                    <div>
                        <div class='text-sm font-medium text-gray-900'>{$value}</div>
                        <div class='text-sm text-gray-500'>ID: {$user->id}</div>
                    </div>
                </div>
            ";
        });

        // Email - searchable
        $grid->column('email', 'Email')->searchable()->sortable()->display(function($value, $user) {
            $verified = $user->email_verified_at ?
                '<span class="text-green-600">✓</span>' :
                '<span class="text-red-600">✗</span>';
            return "{$value} {$verified}";
        });

        // Created date - with date range filter
        $grid->column('created_at', 'Member Since')->sortable()->display(function($value) {
            return date('M d, Y') . '<br><span class="text-xs text-gray-500">' .
                   \Carbon\Carbon::parse($value)->diffForHumans() . '</span>';
        });

        // Email verification status
        $grid->column('email_verified_at', 'Email Status')->display(function($value) {
            if ($value) {
                return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Verified</span>';
            } else {
                return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Not Verified</span>';
            }
        });

        // Add comprehensive filters for existing fields
        $grid->addTextFilter('name', 'Name');
        $grid->addTextFilter('email', 'Email');
        $grid->addDateRangeFilter('created_at', 'Member Since');
        $grid->addFilter('email_verified_at', 'Email Status', [
            'verified' => 'Verified',
            'unverified' => 'Not Verified'
        ], 'select');

        // Configure settings
        $grid->perPage(15)
            ->defaultSort('created_at', 'desc')
            ->search(false)
            ->filters(true)
            ->pagination(true)
            ->title('Users Grid with Enhanced Search & Filters')
            ->description('Advanced user management with comprehensive search and filtering capabilities')
            ->createButton('/users/create', 'Add New User');

        return view('userpanel::data-view', [
            'grid' => $grid,
            'title' => 'Users Grid with Enhanced Search & Filters'
        ]);
    }

    public function productsWithFilters()
    {
        // Create a grid for products with various filter types
        $grid = new DataViewService(new Product);

        // ID column
        $grid->id('ID')->sortable();

        // Name - searchable
        $grid->column('name', 'Product Name')->searchable()->sortable();

        // Description - searchable
        $grid->column('description', 'Description')->searchable()->display(function($value) {
            return strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value;
        });

        // Price - with numeric filter
        $grid->column('price', 'Price')->sortable()->display(function($value) {
            return '$' . number_format($value, 2);
        });

        // Status - with select filter
        $grid->column('status', 'Status')->display(function($value) {
            $colors = [
                'active' => 'bg-green-100 text-green-800',
                'inactive' => 'bg-red-100 text-red-800',
                'draft' => 'bg-gray-100 text-gray-800'
            ];
            $color = $colors[$value] ?? 'bg-gray-100 text-gray-800';
            return "<span class='px-2 py-1 text-xs font-semibold rounded-full {$color}'>" . ucfirst($value) . "</span>";
        });

        // Created date - with date range filter
        $grid->column('created_at', 'Created At')->sortable()->display(function($value) {
            return date('M d, Y', strtotime($value));
        });

        // Add various filter types
        $grid->addTextFilter('name', 'Product Name');
        $grid->addTextFilter('description', 'Description');
        $grid->addFilter('status', 'Status', [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'draft' => 'Draft'
        ], 'select');
        $grid->addDateRangeFilter('created_at', 'Created Date');

        // Configure settings
        $grid->perPage(12)
            ->defaultSort('created_at', 'desc')
            ->search(true)
            ->filters(true)
            ->pagination(true)
            ->title('Products Grid with Multiple Filter Types')
            ->description('Product management with various filter types including dropdowns and date ranges')
            ->createButton('/products/create', 'Add New Product');

        return view('userpanel::data-view', [
            'grid' => $grid,
            'title' => 'Products Grid with Multiple Filter Types'
        ]);
    }

    public function advancedExample()
    {
        // Create an advanced grid with all features
        $grid = new DataViewService(new User);

        // ID column
        $grid->id('ID')->sortable();

        // Name with avatar
        $grid->column('name', 'User')->searchable()->sortable()->display(function($value, $user) {
            $avatar = $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($value) . '&color=7C3AED&background=EBF4FF';
            return "
                <div class='flex items-center'>
                    <img class='h-8 w-8 rounded-full mr-3' src='{$avatar}' alt='{$value}'>
                    <div>
                        <div class='text-sm font-medium text-gray-900'>{$value}</div>
                        <div class='text-sm text-gray-500'>ID: {$user->id}</div>
                    </div>
                </div>
            ";
        });

        // Email with verification status
        $grid->column('email', 'Email')->searchable()->sortable()->display(function($value, $user) {
            $verified = $user->email_verified_at ?
                '<span class="text-green-600">✓</span>' :
                '<span class="text-red-600">✗</span>';
            return "{$value} {$verified}";
        });

        // Role (assuming you have a role field)
        $grid->column('role', 'Role')->searchable()->display(function($value) {
            $colors = [
                'admin' => 'bg-red-100 text-red-800',
                'editor' => 'bg-blue-100 text-blue-800',
                'user' => 'bg-gray-100 text-gray-800'
            ];
            $color = $colors[$value] ?? 'bg-gray-100 text-gray-800';
            return "<span class='px-2 py-1 text-xs font-semibold rounded-full {$color}'>" . ucfirst($value) . "</span>";
        });

        // Created date
        $grid->column('created_at', 'Member Since')->sortable()->display(function($value) {
            return date('M d, Y') . '<br><span class="text-xs text-gray-500">' .
                   \Carbon\Carbon::parse($value)->diffForHumans() . '</span>';
        });

        // Actions
        $grid->actions([
            [
                'label' => 'View',
                'url' => function($user) { return route('userpanel.test.show', $user->id); },
                'class' => 'px-3 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600',
                'icon' => 'fas fa-eye'
            ],
            [
                'label' => 'Edit',
                'url' => function($user) { return route('userpanel.test.edit', $user->id); },
                'class' => 'px-3 py-1 text-xs bg-green-500 text-white rounded hover:bg-green-600',
                'icon' => 'fas fa-edit'
            ]
        ]);

        // Bulk actions
        $grid->bulkActions([
            [
                'label' => 'Delete Selected',
                'action' => 'delete',
                'class' => 'bg-red-600 hover:bg-red-700'
            ]
        ]);

        // Add comprehensive filters
        $grid->addTextFilter('name', 'Name');
        $grid->addTextFilter('email', 'Email');
        $grid->addFilter('role', 'Role', [
            'admin' => 'Administrator',
            'editor' => 'Editor',
            'user' => 'User'
        ], 'select');
        $grid->addDateRangeFilter('created_at', 'Member Since');

        // Configure advanced settings
        $grid->perPage(20)
            ->defaultSort('created_at', 'desc')
            ->search(true)
            ->filters(true)
            ->pagination(true)
            ->attribute('data-grid-type', 'advanced-example');

        return view('userpanel::data-view', [
            'grid' => $grid,
            'title' => 'Advanced Grid Example with All Features'
        ]);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('userpanel::test-show', [
            'user' => $user,
            'title' => "User Details: {$user->name}"
        ]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('userpanel::test-edit', [
            'user' => $user,
            'title' => "Edit User: {$user->name}"
        ]);
    }
}
