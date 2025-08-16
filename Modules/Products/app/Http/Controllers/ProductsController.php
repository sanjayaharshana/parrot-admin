<?php

namespace Modules\Products\Http\Controllers;

use Modules\Products\Models\Products;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\ResourceController;
use Modules\UserPanel\Services\ResourceService;

class ProductsController extends ResourceController
{
    public $icon = 'fa fa-cube';
    public $model = Products::class;
    public $routeName = 'products';

    protected function makeResource(): ResourceService
    {
        return (new ResourceService(Products::class, 'products'))
            ->title('Products Management')
            ->description('Manage products records')
            ->enableTabs()
            
            // Basic Information Tab
            ->tab('basic', 'Basic Information', 'fa fa-info-circle')
                ->text('name')
                    ->required()
                    ->searchable()
                    ->sortable()
                    ->placeholder('Enter product name')
                ->textarea('description')
                    ->placeholder('Enter product description')
                    ->height(120)
                ->number('price')
                    ->required()
                    ->searchable()
                    ->sortable()
                    ->placeholder('0.00')
                    ->rules(['min:0', 'numeric'])
                ->text('sku')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Stock Keeping Unit')
                ->text('slug')
                    ->searchable()
                    ->sortable()
                    ->placeholder('product-url-slug')
                ->select('category')
                    ->searchable()
                    ->sortable()
                    ->options([
                        'electronics' => 'Electronics',
                        'clothing' => 'Clothing',
                        'books' => 'Books',
                        'home' => 'Home & Garden',
                        'sports' => 'Sports & Outdoors',
                        'other' => 'Other'
                    ])
                    ->placeholder('Select category')
                ->select('brand')
                    ->searchable()
                    ->sortable()
                    ->options([
                        'apple' => 'Apple',
                        'samsung' => 'Samsung',
                        'nike' => 'Nike',
                        'adidas' => 'Adidas',
                        'generic' => 'Generic',
                        'other' => 'Other'
                    ])
                    ->placeholder('Select brand')
            ->end()
            
            // Media & Images Tab
            ->tab('media', 'Media & Images', 'fa fa-image')
                ->file('image')
                    ->accept('image/*')
                    ->placeholder('Upload main product image')
                ->text('thumbnail')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Thumbnail URL or path')
                ->customHtml('
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fa fa-info-circle text-blue-500 mr-2"></i>
                            <span class="text-blue-800 text-sm">
                                <strong>Image Guidelines:</strong> Recommended size: 800x800px, Max file size: 2MB, 
                                Supported formats: JPG, PNG, GIF, WebP
                            </span>
                        </div>
                    </div>
                ', 'Image Guidelines', 'mb-4')
            ->end()
            
            // Inventory & Stock Tab
            ->tab('inventory', 'Inventory & Stock', 'fa fa-boxes')
                ->number('stock_quantity')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Available quantity')
                    ->rules(['min:0', 'integer'])
                ->select('stock_status')
                    ->searchable()
                    ->sortable()
                    ->options([
                        'in_stock' => 'In Stock',
                        'out_of_stock' => 'Out of Stock',
                        'low_stock' => 'Low Stock',
                        'backorder' => 'Backorder',
                        'discontinued' => 'Discontinued'
                    ])
                    ->placeholder('Select stock status')
                ->number('weight')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Weight in grams')
                    ->rules(['min:0', 'numeric'])
                ->text('dimensions')
                    ->searchable()
                    ->sortable()
                    ->placeholder('L x W x H (cm)')
                ->text('barcode')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Product barcode/UPC')
            ->end()
            
            // Pricing & Subscription Tab
            ->tab('pricing', 'Pricing & Subscription', 'fa fa-dollar-sign')
                ->number('subscription_price')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Monthly subscription price')
                    ->rules(['min:0', 'numeric'])
                ->select('subscription_interval')
                    ->searchable()
                    ->sortable()
                    ->options([
                        'monthly' => 'Monthly',
                        'quarterly' => 'Quarterly',
                        'yearly' => 'Yearly',
                        'weekly' => 'Weekly',
                        'daily' => 'Daily'
                    ])
                    ->placeholder('Select subscription interval')
                ->textarea('subscription_terms')
                    ->placeholder('Subscription terms and conditions')
                    ->height(100)
            ->end()
            
            // Product Settings Tab
            ->tab('settings', 'Product Settings', 'fa fa-cog')
                ->switch('is_active')
                    ->searchable()
                    ->sortable()
                    ->label('Product Active')
                ->switch('is_featured')
                    ->searchable()
                    ->sortable()
                    ->label('Featured Product')
                ->switch('is_visible')
                    ->searchable()
                    ->sortable()
                    ->label('Visible to Customers')
                ->switch('is_featured_on_homepage')
                    ->searchable()
                    ->sortable()
                    ->label('Featured on Homepage')
                ->switch('is_new')
                    ->searchable()
                    ->sortable()
                    ->label('New Product')
                ->switch('is_on_sale')
                    ->searchable()
                    ->sortable()
                    ->label('On Sale')
                ->switch('is_taxable')
                    ->searchable()
                    ->sortable()
                    ->label('Taxable Product')
                ->switch('is_digital')
                    ->searchable()
                    ->sortable()
                    ->label('Digital Product')
                ->switch('is_subscription')
                    ->searchable()
                    ->sortable()
                    ->label('Subscription Product')
                ->number('sort_order')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Display order (lower numbers first)')
                    ->rules(['integer'])
            ->end()
            
            // SEO & Meta Tab
            ->tab('seo', 'SEO & Meta', 'fa fa-search')
                ->text('meta_title')
                    ->searchable()
                    ->sortable()
                    ->placeholder('SEO title (max 60 characters)')
                    ->rules(['max:60'])
                ->textarea('meta_description')
                    ->placeholder('SEO description (max 160 characters)')
                    ->height(100)
                    ->rules(['max:160'])
                ->text('meta_keywords')
                    ->searchable()
                    ->sortable()
                    ->placeholder('SEO keywords (comma separated)')
            ->end()
            
            // Additional Information Tab
            ->tab('additional', 'Additional Information', 'fa fa-plus-circle')
                ->text('vendor')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Product vendor/supplier')
                ->text('warranty')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Warranty information')
                ->textarea('additional_info')
                    ->placeholder('Additional product information')
                    ->height(100)
                ->textarea('custom_fields')
                    ->placeholder('Custom fields (JSON format)')
                    ->height(100)
            ->end()
            
            ->actions([
                'view' => [ 'label' => 'View', 'icon' => 'fa fa-eye', 'route' => 'show' ],
                'edit' => [ 'label' => 'Edit', 'icon' => 'fa fa-edit', 'route' => 'edit' ],
                'delete' => [ 'label' => 'Delete', 'icon' => 'fa fa-trash', 'route' => 'destroy', 'method' => 'DELETE', 'confirm' => true ],
            ])
            ->bulkActions([
                'delete' => [ 'label' => 'Delete Selected', 'icon' => 'fa fa-trash', 'confirm' => true ],
            ]);
    }

    public function dataView()
    {
        $dataView = new \Modules\UserPanel\Services\DataViewService(new Products());

        $dataView->title('Products Management')
            ->description('Manage products records')
            ->routePrefix('products')
            ->perPage(15)
            ->defaultSort('id', 'desc')
            ->pagination(true)
            ->search(true);

        // ID column
        $dataView->id('ID')->sortable();

        $dataView->column('name', 'Name')
            ->sortable()
            ->searchable();


        $dataView->column('price', 'Price')
            ->sortable()
            ->searchable();

        $dataView->column('sku', 'Sku')
            ->sortable()
            ->searchable();


        $dataView->column('is_active', 'Is Active')
            ->sortable()
            ->searchable();


        $dataView->column('image', 'Image')
            ->sortable()
            ->searchable();

        $dataView->column('thumbnail', 'Thumbnail')
            ->sortable()
            ->searchable();

        $dataView->column('barcode', 'Barcode')
            ->sortable()
            ->searchable();

        // Actions
        $dataView->actions([
            'view' => [ 'label' => 'View', 'icon' => 'fa fa-eye', 'route' => 'show' ],
            'edit' => [ 'label' => 'Edit', 'icon' => 'fa fa-edit', 'route' => 'edit' ],
            'delete' => [ 'label' => 'Delete', 'icon' => 'fa fa-trash', 'route' => 'destroy', 'method' => 'DELETE', 'confirm' => true ],
        ]);

        // Bulk actions
        $dataView->bulkActions([
            'delete' => [ 'label' => 'Delete Selected', 'icon' => 'fa fa-trash', 'confirm' => true ],
        ]);

        // Create button
        $dataView->createButton(route('products.create'), 'Create New');

        return $dataView;
    }
}
