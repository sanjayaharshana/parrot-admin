<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\BaseController;
use Modules\UserPanel\Services\DataViewService;

class ProductController extends BaseController
{
    public $icon = 'fa fa-box';
    public $showInSidebar = true;
    public $model = Product::class;

    /**
     * Create form layout for creating/editing products
     */
    public function createForm()
    {
        $layout = $this->layoutService;
        $layout->setFormService($this->form);
        
        // First row with product basic info
        $basicInfoRow = $layout->row();
        $basicInfoRow->column(8, function ($form, $column) use ($layout) {
            $column->addField(
                $form->text()
                    ->name('name')
                    ->label('Product Name')
                    ->placeholder('Enter product name')
                    ->required()
            );
            
            $column->addField(
                $form->textarea()
                    ->name('description')
                    ->label('Description')
                    ->placeholder('Enter product description')
            );
        });
        
        $basicInfoRow->column(4, function ($form, $column) use ($layout) {
            $column->addField(
                $form->select()
                    ->name('category_id')
                    ->label('Category')
                    ->options(function () {
                        // Replace with your actual categories
                        return [
                            1 => 'Electronics',
                            2 => 'Clothing',
                            3 => 'Books',
                            4 => 'Home & Garden'
                        ];
                    })
                    ->required()
            );
            
            $column->addField(
                $form->number()
                    ->name('price')
                    ->label('Price')
                    ->placeholder('0.00')
                    ->step('0.01')
                    ->required()
            );
        });

        // Second row with additional details
        $detailsRow = $layout->row();
        $detailsRow->column(6, function ($form, $column) use ($layout) {
            $column->addField(
                $form->number()
                    ->name('stock_quantity')
                    ->label('Stock Quantity')
                    ->placeholder('0')
                    ->required()
            );
            
            $column->addField(
                $form->text()
                    ->name('sku')
                    ->label('SKU')
                    ->placeholder('Enter SKU code')
            );
        });
        
        $detailsRow->column(6, function ($form, $column) use ($layout) {
            $column->addField(
                $form->select()
                    ->name('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'draft' => 'Draft'
                    ])
                    ->required()
            );
            
            $column->addField(
                $form->file()
                    ->name('image')
                    ->label('Product Image')
                    ->accept('image/*')
            );
        });

        // Third row with custom content
        $customRow = $layout->row();
        $customRow->column(12, function ($form, $column) use ($layout) {
            $column->addHtml('<div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Tip:</strong> Make sure to provide accurate product information. This will help customers make informed purchasing decisions.
                        </p>
                    </div>
                </div>
            </div>');
        });

        return $layout->render();
    }

    /**
     * Data grid view for listing products
     */
    public function dataSetView()
    {
        $grid = new DataViewService(new Product());

        // ID column
        $grid->id('ID')->sortable();

        // Product name with image
        $grid->column('name', 'Product')->display(function($value, $product) {
            $image = $product->image ?? 'https://via.placeholder.com/40x40?text=P';
            $sku = $product->sku ?? 'N/A';
            return "
                <div class='flex items-center'>
                    <img class='h-10 w-10 rounded mr-3 object-cover' src='{$image}' alt='{$value}'>
                    <div>
                        <div class='text-sm font-medium text-gray-900'>{$value}</div>
                        <div class='text-sm text-gray-500'>SKU: {$sku}</div>
                    </div>
                </div>
            ";
        });

        // Category
        $grid->column('category', 'Category')->display(function($value) {
            $colors = [
                'Electronics' => 'bg-blue-100 text-blue-800',
                'Clothing' => 'bg-green-100 text-green-800',
                'Books' => 'bg-yellow-100 text-yellow-800',
                'Home & Garden' => 'bg-purple-100 text-purple-800'
            ];
            $color = $colors[$value] ?? 'bg-gray-100 text-gray-800';
            return "<span class='px-2 py-1 text-xs font-semibold rounded-full {$color}'>{$value}</span>";
        });

        // Price
        $grid->column('price', 'Price')->display(function($value) {
            return '$' . number_format($value, 2);
        });

        // Stock quantity with status
        $grid->column('stock_quantity', 'Stock')->display(function($value) {
            $status = $value > 0 ? 'In Stock' : 'Out of Stock';
            $color = $value > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
            return "<span class='px-2 py-1 text-xs font-semibold rounded-full {$color}'>{$value} ({$status})</span>";
        });

        // Status
        $grid->column('status', 'Status')->display(function($value) {
            $colors = [
                'active' => 'bg-green-100 text-green-800',
                'inactive' => 'bg-red-100 text-red-800',
                'draft' => 'bg-yellow-100 text-yellow-800'
            ];
            $color = $colors[$value] ?? 'bg-gray-100 text-gray-800';
            return "<span class='px-2 py-1 text-xs font-semibold rounded-full {$color}'>" . ucfirst($value) . "</span>";
        });

        // Created date
        $grid->column('created_at', 'Created')->display(function($value) {
            return date('M d, Y', strtotime($value));
        });

        // Actions
        $grid->actions([
            [
                'label' => 'View',
                'url' => function($product) {
                    return route('products.show', $product->id);
                },
                'class' => 'px-3 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600',
                'icon' => 'fas fa-eye'
            ],
            [
                'label' => 'Edit',
                'url' => function($product) {
                    return route('products.edit', $product->id);
                },
                'class' => 'px-3 py-1 text-xs bg-yellow-500 text-white rounded hover:bg-yellow-600',
                'icon' => 'fas fa-edit'
            ],
            [
                'label' => 'Delete',
                'url' => function($product) {
                    return route('products.destroy', $product->id);
                },
                'class' => 'px-3 py-1 text-xs bg-red-500 text-white rounded hover:bg-red-600',
                'icon' => 'fas fa-trash',
                'onclick' => 'return confirm("Are you sure you want to delete this product?")'
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

        // Configure grid settings
        $grid->perPage(15)
            ->defaultSort('created_at', 'desc')
            ->search(true)
            ->filters(true)
            ->pagination(true);

        return $grid->render();
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|integer',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'status' => 'required|in:active,inactive,draft',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        // Create product
        $product = Product::create($data);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Show the specified product
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);

        return view('userpanel::show', [
            'product' => $product,
            'title' => "Product Details: {$product->name}"
        ]);
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        
        // Pre-populate form with existing data
        $this->form->setData($product->toArray());
        
        $layout = $this->createForm();
        
        return view('userpanel::edit', [
            'layout' => $layout,
            'product' => $product,
            'title' => "Edit Product: {$product->name}"
        ]);
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|integer',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $id,
            'status' => 'required|in:active,inactive,draft',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                \Storage::disk('public')->delete($product->image);
            }
            
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Delete associated image
        if ($product->image) {
            \Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }

    /**
     * Bulk actions for multiple products
     */
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'No products selected.');
        }

        $products = Product::whereIn('id', $ids);

        switch ($action) {
            case 'activate':
                $products->update(['status' => 'active']);
                $message = 'Selected products activated successfully!';
                break;

            case 'deactivate':
                $products->update(['status' => 'inactive']);
                $message = 'Selected products deactivated successfully!';
                break;

            case 'delete':
                // Delete associated images
                $productsToDelete = $products->get();
                foreach ($productsToDelete as $product) {
                    if ($product->image) {
                        \Storage::disk('public')->delete($product->image);
                    }
                }
                $products->delete();
                $message = 'Selected products deleted successfully!';
                break;

            default:
                return back()->with('error', 'Invalid action.');
        }

        return back()->with('success', $message);
    }
} 