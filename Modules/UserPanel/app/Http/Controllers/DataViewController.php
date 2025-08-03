<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\BaseController;
use Modules\UserPanel\Services\DataViewService;
use App\Models\User;

class DataViewController extends BaseController
{
    // Set to true to show in sidebar
    public $showInSidebar = true;
    public $model = User::class;

    public function index()
    {


        return view('userpanel::data-view', [

        ]);
    }

    public function advanced()
    {
        // Create a more advanced grid with relationships
        $grid = new DataViewService(new User);

        // ID column
        $grid->id('ID')->sortable();

        // Name with avatar - make it searchable
        $grid->column('name', 'User')->searchable()->display(function($value, $user) {
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

        // Email with verification status - make it searchable
        $grid->column('email', 'Email')->searchable()->display(function($value, $user) {
            $verified = $user->email_verified_at ?
                '<span class="text-green-600">✓</span>' :
                '<span class="text-red-600">✗</span>';
            return "{$value} {$verified}";
        });

        // Role (assuming you have a role field or relationship) - make it searchable and add filter
        $grid->column('role', 'Role')->searchable()->display(function($value) {
            $colors = [
                'admin' => 'bg-red-100 text-red-800',
                'editor' => 'bg-blue-100 text-blue-800',
                'user' => 'bg-gray-100 text-gray-800'
            ];
            $color = $colors[$value] ?? 'bg-gray-100 text-gray-800';
            return "<span class='px-2 py-1 text-xs font-semibold rounded-full {$color}'>" . ucfirst($value) . "</span>";
        });

        // Last login (assuming you have this field) - add date range filter
        $grid->column('last_login_at', 'Last Login')->display(function($value) {
            if (!$value) return '<span class="text-gray-400">Never</span>';
            return date('M d, Y H:i', strtotime($value));
        });

        // Created date with relative time - add date range filter
        $grid->column('created_at', 'Member Since')->display(function($value) {
            return date('M d, Y') . '<br><span class="text-xs text-gray-500">' .
                   \Carbon\Carbon::parse($value)->diffForHumans() . '</span>';
        });

        // Status with toggle - add select filter
        $grid->column('is_active', 'Status')->display(function($value, $user) {
            $status = $value ? 'Active' : 'Inactive';
            $color = $value ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
            return "<span class='px-2 py-1 text-xs font-semibold rounded-full {$color}'>{$status}</span>";
        });

        // Actions with dropdown
        $grid->actions([
            [
                'label' => 'Quick Actions',
                'url' => '#',
                'class' => 'px-3 py-1 text-xs bg-purple-500 text-white rounded hover:bg-purple-600',
                'icon' => 'fas fa-cog'
            ]
        ]);

        // Bulk actions
        $grid->bulkActions([
            [
                'label' => 'Activate Selected',
                'action' => 'activate',
                'class' => 'bg-green-500 hover:bg-green-600'
            ],
            [
                'label' => 'Deactivate Selected',
                'action' => 'deactivate',
                'class' => 'bg-red-500 hover:bg-red-600'
            ],
            [
                'label' => 'Delete Selected',
                'action' => 'delete',
                'class' => 'bg-red-600 hover:bg-red-700'
            ]
        ]);

        // Add filters
        $grid->addFilter('role', 'Role', [
            'admin' => 'Administrator',
            'editor' => 'Editor',
            'user' => 'User'
        ], 'select');

        $grid->addFilter('is_active', 'Status', [
            '1' => 'Active',
            '0' => 'Inactive'
        ], 'select');

        $grid->addDateRangeFilter('created_at', 'Member Since');
        $grid->addDateRangeFilter('last_login_at', 'Last Login');
        $grid->addTextFilter('name', 'Name');

        // Configure advanced settings
        $grid->perPage(15)
            ->defaultSort('created_at', 'desc')
            ->search(true)
            ->filters(true)
            ->pagination(true)
            ->attribute('data-grid-type', 'advanced-users');

        return view('userpanel::data-view', [
            'grid' => $grid,
            'title' => 'Advanced Users Data Grid with Search & Filters'
        ]);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('userpanel::data-view-show', [
            'user' => $user,
            'title' => "User Details: {$user->name}"
        ]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('userpanel::data-view-edit', [
            'user' => $user,
            'title' => "Edit User: {$user->name}"
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('userpanel.data-view')
            ->with('success', 'User deleted successfully!');
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'No users selected.');
        }

        $users = User::whereIn('id', $ids);

        switch ($action) {
            case 'activate':
                $users->update(['is_active' => true]);
                $message = 'Selected users activated successfully!';
                break;

            case 'deactivate':
                $users->update(['is_active' => false]);
                $message = 'Selected users deactivated successfully!';
                break;

            case 'delete':
                $users->delete();
                $message = 'Selected users deleted successfully!';
                break;

            default:
                return back()->with('error', 'Invalid action.');
        }

        return back()->with('success', $message);
    }
}
