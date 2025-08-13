<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\UserPanel\Http\Base\ResourceController;
use Modules\UserPanel\Services\ResourceService;

class ProductController extends ResourceController
{
    public $icon = 'fa fa-box';
    public $model = Product::class;
    public $routeName = 'products';

    /**
     * Make the resource instance
     */
    public function index()
    {
        if (request()->boolean('json')) {
            $query = Product::query();
            if ($q = request('q')) {
                $query->where('name', 'like', "%{$q}%");
            }
            return response()->json($query->limit(50)->get(['id','name','price']));
        }

        return parent::index();
    }

    protected function makeResource(): ResourceService
    {
        return (new ResourceService(Product::class, 'products'))
            ->title('Product Management')
            ->description('Manage your product catalog with comprehensive information')

            // Enable tabs for better organization
            ->enableTabs()

            // Basic Information Tab
            ->tab('basic', 'Basic Information', 'fa fa-info-circle')
                ->text('name')
                    ->required()
                    ->searchable()
                    ->sortable()
                    ->rules(['max:255'])
                ->text('sku')
                    ->required()
                    ->searchable()
                    ->sortable()
                    ->rules(['max:100', 'unique:products,sku'])
                ->number('price')
                    ->required()
                    ->searchable()
                    ->sortable()
                    ->rules(['numeric', 'min:0'])
                ->number('stock')
                    ->required()
                    ->searchable()
                    ->sortable()
                    ->rules(['integer', 'min:0'])
                ->divider('Product Guidelines')
                ->alert('Product names should be descriptive and SKUs should be unique identifiers.', 'info')
                ->end()

            // Description & Category Tab
            ->tab('details', 'Description & Category', 'fa fa-tags')
                ->textarea('description')
                    ->required()
                    ->searchable()
                    ->rules(['string'])
                ->select('category')
                    ->required()
                    ->searchable()
                    ->sortable()
                    ->options([
                        'electronics' => 'Electronics',
                        'clothing' => 'Clothing',
                        'books' => 'Books',
                        'home' => 'Home & Garden',
                        'sports' => 'Sports & Outdoors'
                    ])
                    ->rules(['in:electronics,clothing,books,home,sports'])
                ->checkbox('is_active')
                    ->searchable()
                    ->sortable()
                ->divider('Category Information')
                ->customHtml(
                    '<p class="text-sm text-gray-600">Choose the most appropriate category for your product. This helps customers find your products more easily.</p>',
                    'Category Help',
                    'bg-blue-50 border border-blue-200 rounded-lg p-4'
                )
                ->end()

            // Media & Files Tab
            ->tab('media', 'Media & Files', 'fa fa-image')
                ->file('image')
                    ->accept('image/*')
                    ->rules(['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'])
                ->divider('Image Guidelines')
                ->alert('Upload high-quality product images (JPG, PNG, GIF up to 2MB) for better customer experience.', 'info')
                ->customHtml(
                    '<div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-2">Image Requirements:</h4>
                        <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                            <li>Minimum resolution: 800x600 pixels</li>
                            <li>Maximum file size: 2MB</li>
                            <li>Supported formats: JPG, PNG, GIF</li>
                            <li>Use clear, well-lit product photos</li>
                        </ul>
                    </div>',
                    'Image Requirements',
                    'bg-gray-50 border border-gray-200 rounded-lg p-4'
                )
                ->end()

            // Settings & Actions Tab
            ->tab('settings', 'Settings & Actions', 'fa fa-cog')
                ->customHtml(
                    '<div class="flex space-x-4 mb-4">
                        <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            <i class="fa fa-eye mr-2"></i>Preview Product
                        </button>
                        <button type="button" class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            <i class="fa fa-save mr-2"></i>Save Draft
                        </button>
                    </div>',
                    'Quick Actions',
                    'bg-gray-50 border border-gray-200 rounded-lg p-4'
                )
                ->divider('Support Information')
                ->customHtml(
                    '<p class="text-sm text-gray-600">Need help? Contact our support team at
                    <a href="mailto:support@example.com" class="text-blue-600 hover:text-blue-800">support@example.com</a>
                    or call us at <span class="font-medium">+1-555-123-4567</span></p>',
                    'Need Help?',
                    'bg-gray-50 border border-gray-200 rounded-lg p-4'
                )
                ->end()

            // Configure actions
            ->actions([
                'view' => [
                    'label' => 'View',
                    'icon' => 'fa fa-eye',
                    'class' => 'btn-sm btn-info',
                    'route' => 'show'
                ],
                'edit' => [
                    'label' => 'Edit',
                    'icon' => 'fa fa-edit',
                    'class' => 'btn-sm btn-primary',
                    'route' => 'edit'
                ],
                'delete' => [
                    'label' => 'Delete',
                    'icon' => 'fa fa-trash',
                    'class' => 'btn-sm btn-danger',
                    'route' => 'destroy',
                    'method' => 'DELETE',
                    'confirm' => true
                ]
            ])

            // Configure bulk actions
            ->bulkActions([
                'delete' => [
                    'label' => 'Delete Selected',
                    'icon' => 'fa fa-trash',
                    'class' => 'btn-danger',
                    'confirm' => true
                ],
                'activate' => [
                    'label' => 'Activate Selected',
                    'icon' => 'fa fa-check',
                    'class' => 'btn-success'
                ],
                'deactivate' => [
                    'label' => 'Deactivate Selected',
                    'icon' => 'fa fa-times',
                    'class' => 'btn-warning'
                ]
            ]);
    }

    public function dataView()
    {
        $dataView = new \Modules\UserPanel\Services\DataViewService(new Product());

        // Add custom query scope for business logic
        $dataView->addQueryScope(function($query) {
            // Only show active products by default
            $query->where('status', 'inactive');
        });

        // Configure the data view
        $dataView->title('Product Management')
            ->description('Manage your product catalog with this comprehensive grid view. You can sort, filter, and search through product data seamlessly.')
            ->routePrefix('products')
            ->perPage(15)
            ->defaultSort('created_at', 'desc')
            ->pagination(true)
            ->search(true)
            ->filters(true);

        // Add ID column
        $dataView->id('ID')->sortable();

        // Add name column
        $dataView->column('name', 'Product Name')
            ->sortable()
            ->searchable();

        // Add SKU column
        $dataView->column('sku', 'SKU')
            ->sortable()
            ->searchable();

        // Add price column with formatting
        $dataView->column('price', 'Price')
            ->display(function($value) {
                return '$' . number_format($value, 2);
            })
            ->sortable();

        // Add stock column with status indicators
        $dataView->column('stock', 'Stock')
            ->display(function($value) {
                if ($value > 10) {
                    return '<span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">' . $value . '</span>';
                } elseif ($value > 0) {
                    return '<span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">' . $value . '</span>';
                } else {
                    return '<span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Out of Stock</span>';
                }
            })
            ->sortable();

        // Add category column
        $dataView->column('category', 'Category')
            ->display(function($value) {
                return ucfirst($value);
            })
            ->sortable();

        // Add status column
        $dataView->column('is_active', 'Status')
            ->display(function($value) {
                if ($value) {
                    return '<span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Active</span>';
                } else {
                    return '<span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Inactive</span>';
                }
            })
            ->sortable();

        // Add created date column
        $dataView->column('created_at', 'Created')
            ->display(function($value) {
                return date('M d, Y', strtotime($value));
            })
            ->sortable();

        // Add actions column
        $dataView->actions([
            [
                'label' => 'View',
                'url' => function($product) {
                    return route('products.show', $product->id);
                },
                'class' => 'px-3 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600'
            ],
            [
                'label' => 'Edit',
                'url' => function($product) {
                    return route('products.edit', $product->id);
                },
                'class' => 'px-3 py-1 text-xs bg-yellow-500 text-white rounded hover:bg-yellow-600'
            ],
            [
                'label' => 'Delete',
                'url' => function($product) {
                    return route('products.destroy', $product->id);
                },
                'class' => 'px-3 py-1 text-xs bg-red-500 text-white rounded hover:bg-red-600',
                'confirm' => true
            ]
        ]);

        // Add bulk actions
        $dataView->bulkActions([
            'delete' => [
                'label' => 'Delete Selected',
                'icon' => 'fa fa-trash',
                'class' => 'btn-danger',
                'confirm' => true
            ],
            'activate' => [
                'label' => 'Activate Selected',
                'icon' => 'fa fa-check',
                'class' => 'btn-success'
            ],
            'deactivate' => [
                'label' => 'Deactivate Selected',
                'icon' => 'fa fa-times',
                'class' => 'btn-warning'
            ]
        ]);

        // Add advanced filters
        $dataView->addTextFilter('name', 'Product Name');
        $dataView->addTextFilter('sku', 'SKU');

        // Add select filter for category
        $dataView->addFilter('category', 'Category', [
            'electronics' => 'Electronics',
            'clothing' => 'Clothing',
            'books' => 'Books',
            'home' => 'Home & Garden',
            'sports' => 'Sports & Outdoors'
        ], 'select');

        // Add select filter for status
        $dataView->addFilter('is_active', 'Status', [
            '1' => 'Active',
            '0' => 'Inactive'
        ], 'select');

        // Add numeric range filter for price
        $dataView->addNumericRangeFilter('price', 'Price Range');

        // Add numeric range filter for stock
        $dataView->addNumericRangeFilter('stock', 'Stock Range');

        // Add relationship filter for vendor (if you have vendor relationship)
        // $dataView->addRelationshipFilter('vendor_id', 'Vendor', 'vendor', 'name');

        // Add custom filter for availability
        $dataView->addCustomFilter('availability', 'Availability', function($query, $value) {
            switch ($value) {
                case 'in_stock':
                    $query->where('stock', '>', 0);
                    break;
                case 'low_stock':
                    $query->whereBetween('stock', [1, 10]);
                    break;
                case 'out_of_stock':
                    $query->where('stock', 0);
                    break;
            }
        });

        // Add date range filter
        $dataView->addDateRangeFilter('created_at', 'Created Date');

        // Add create button
        $dataView->createButton(route('products.create'), 'Create New Product');

        return $dataView;
    }

    /**
     * Handle bulk actions
     */
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'No products selected.');
        }

        try {
            switch ($action) {
                case 'delete':
                    $products = Product::whereIn('id', $ids)->get();
                    foreach ($products as $product) {
                        if ($product->image && Storage::disk('public')->exists($product->image)) {
                            Storage::disk('public')->delete($product->image);
                        }
                    }
                    Product::whereIn('id', $ids)->delete();
                    $message = 'Selected products deleted successfully!';
                    break;

                case 'activate':
                    Product::whereIn('id', $ids)->update(['is_active' => true]);
                    $message = 'Selected products activated successfully!';
                    break;

                case 'deactivate':
                    Product::whereIn('id', $ids)->update(['is_active' => false]);
                    $message = 'Selected products deactivated successfully!';
                    break;

                default:
                    return redirect()->back()->with('error', 'Invalid action.');
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error performing bulk action: ' . $e->getMessage());
        }
    }
}
