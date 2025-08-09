<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Evest;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\PageController;
use Modules\UserPanel\Http\Base\ResourceController;
use Modules\UserPanel\Services\ResourceService;

class DashboardController extends PageController
{
    public $icon = 'fa fa-dashboard';
    public $model = Evest::class;
    public $routeName = 'dashboard';
    public $title = 'Dashboard';

    public function layout()
    {
        // Example dashboard layout
        // Top cards in a responsive grid
        $grid = $this->layoutService->grid(3, 4);

        $grid->item(function ($form, $item) {
            $item->addView('userpanel::components.custom-stats', [
                'stats' => [
                    ['icon' => 'users', 'label' => 'Users', 'value' => number_format(1245)],
                    ['icon' => 'folder', 'label' => 'Projects', 'value' => number_format(87)],
                    ['icon' => 'check', 'label' => 'Tasks Done', 'value' => number_format(342)],
                ],
            ]);
        });

        $grid->item(function ($form, $item) {
            $item->addView('userpanel::components.custom-chart', [
                'chartData' => [
                    'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    'datasets' => [
                        ['label' => 'Sales', 'data' => [12, 19, 3, 5, 2, 3, 14]],
                    ],
                ],
            ]);
        });

        $grid->item(function ($form, $item) {
            $item->addHtml('<div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">'
                . '<h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>'
                . '<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">'
                . '<a href="' . e(route('products.create')) . '" class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm text-center">Create Product</a>'
                . '<a href="' . e(route('ships.create')) . '" class="px-4 py-2 rounded-lg bg-purple-600 text-white text-sm text-center">Create Ship</a>'
                . '</div>'
                . '</div>');
        });

        // Full width custom section
        $this->layoutService->section('Overview', 'High-level summary of platform metrics', function ($form, $section) {
            $section->addHtml('<div class="text-sm text-gray-600">Welcome back, ' . e(auth()->user()->name) . '.</div>');
        });
    }
}
