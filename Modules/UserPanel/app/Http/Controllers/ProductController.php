<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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
        $basicRow = $layout->row();
        $basicRow->column(6, function ($form, $column) use ($layout) {
            $column->addField(
                $form->text()
                    ->name('name')
                    ->label('Product Name')
                    ->placeholder('Enter product name')
                    ->required()
            );
            
            $column->addField(
                $form->text()
                    ->name('sku')
                    ->label('SKU')
                    ->placeholder('Enter product SKU')
                    ->required()
            );
        });
        
        $basicRow->column(6, function ($form, $column) use ($layout) {
            $column->addField(
                $form->number()
                    ->name('price')
                    ->label('Price')
                    ->placeholder('0.00')
                    ->step('0.01')
                    ->required()
            );
            
            $column->addField(
                $form->number()
                    ->name('stock')
                    ->label('Stock Quantity')
                    ->placeholder('0')
                    ->required()
            );
        });

        // Second row with description and category
        $descRow = $layout->row();
        $descRow->column(8, function ($form, $column) use ($layout) {
            $column->addField(
                $form->textarea()
                    ->name('description')
                    ->label('Description')
                    ->placeholder('Enter product description')
                    ->required()
            );
        });
        
        $descRow->column(4, function ($form, $column) use ($layout) {
            $column->addField(
                $form->select()
                    ->name('category')
                    ->label('Category')
                    ->options([
                        'electronics' => 'Electronics',
                        'clothing' => 'Clothing',
                        'books' => 'Books',
                        'home' => 'Home & Garden',
                        'sports' => 'Sports & Outdoors'
                    ])
                    ->required()
            );
            
            $column->addField(
                $form->checkbox()
                    ->name('is_active')
                    ->label('Active')
            );
        });

        // Third row with image upload
        $imageRow = $layout->row();
        $imageRow->column(12, function ($form, $column) use ($layout) {
            $column->addField(
                $form->file()
                    ->name('image')
                    ->label('Product Image')
                    ->accept('image/*')
            );
            
            $column->addHtml('<p class="text-sm text-gray-600 mt-2">Upload a product image (JPG, PNG, GIF up to 2MB)</p>');
        });

        return $layout->render();
    }

    /**
     * Data grid view for listing products
     */
    public function dataSetView()
    {
        $grid = new DataViewService(new Product());

        $grid->title('Product Management');
        $grid->description('Manage your product catalog with this comprehensive grid view. You can sort, filter, and search through product data seamlessly.');

        // Define grid columns
        $grid->id('ID')->sortable();
        
        $grid->column('name', 'Product Name')->sortable();
        
        $grid->column('sku', 'SKU')->sortable();
        
        $grid->column('price', 'Price')->display(function($value) {
            return '$' . number_format($value, 2);
        });
        
        $grid->column('stock', 'Stock')->display(function($value) {
            if ($value > 10) {
                return '<span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">' . $value . '</span>';
            } elseif ($value > 0) {
                return '<span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">' . $value . '</span>';
            } else {
                return '<span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Out of Stock</span>';
            }
        });
        
        $grid->column('category', 'Category')->display(function($value) {
            return ucfirst($value);
        });
        
        $grid->column('is_active', 'Status')->display(function($value) {
            if ($value) {
                return '<span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Active</span>';
            }
            return '<span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Inactive</span>';
        });
        
        $grid->column('created_at', 'Created')->display(function($value) {
            return $value ? date('M d, Y', strtotime($value)) : 'N/A';
        });

        // Add filters
        $grid->addTextFilter('name', 'Product Name');
        $grid->addTextFilter('sku', 'SKU');
        $grid->addFilter('category', 'Category', [
            'electronics' => 'Electronics',
            'clothing' => 'Clothing',
            'books' => 'Books',
            'home' => 'Home & Garden',
            'sports' => 'Sports & Outdoors'
        ], 'select');
        $grid->addFilter('is_active', 'Status', [
            '1' => 'Active',
            '0' => 'Inactive'
        ], 'select');
        $grid->addDateRangeFilter('created_at', 'Created Date');

        // Add create button
        $grid->createButton(url('products/create'), 'Add Product', 'fa fa-plus', 'btn-primary');

        // Configure grid settings
        $grid->perPage(15)
            ->defaultSort('created_at', 'desc')
            ->search(true)
            ->pagination(true);

        return $grid->render();
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'category' => 'required|string|in:electronics,clothing,books,home,sports',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $product = new Product();
            $product->name = $request->name;
            $product->sku = $request->sku;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->description = $request->description;
            $product->category = $request->category;
            $product->is_active = $request->has('is_active');

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
                $product->image = $imagePath;
            }

            $product->save();

            return redirect()->route('products.index')
                ->with('success', 'Product created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating product: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the specified product
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        
        return view('userpanel::show', [
            'product' => $product,
            'title' => 'Product Details'
        ]);
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        
        // Bind the model to the form service
        $this->form->bindModel($product);
        
        $layout = $this->createForm();
        
        return view('userpanel::edit', [
            'layout' => $layout,
            'product' => $product,
            'title' => 'Edit Product'
        ]);
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku,' . $id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'category' => 'required|string|in:electronics,clothing,books,home,sports',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $product->name = $request->name;
            $product->sku = $request->sku;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->description = $request->description;
            $product->category = $request->category;
            $product->is_active = $request->has('is_active');

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                
                $imagePath = $request->file('image')->store('products', 'public');
                $product->image = $imagePath;
            }

            $product->save();

            return redirect()->route('products.index')
                ->with('success', 'Product updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating product: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified product
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            // Delete image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            
            $product->delete();

            return redirect()->route('products.index')
                ->with('success', 'Product deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->route('products.index')
                ->with('error', 'Error deleting product: ' . $e->getMessage());
        }
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