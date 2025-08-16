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
    public $parentMenu = 'Products';

    protected function makeResource(): ResourceService
    {
        return (new ResourceService(Products::class, 'products'))
            ->title('Products Management')
            ->description('Manage products records')
            ->enableTabs()

            // 1. Basic Information Tab
            ->tab('basic', 'Basic Information', 'fa fa-info-circle')
                ->text('name')
                    ->required()
                    ->searchable()
                    ->sortable()
                    ->placeholder('Enter product name')
                    ->help('Product name will be displayed to customers and used in search results')
                ->textarea('description')
                    ->placeholder('Enter product description')
                    ->height(120)
                    ->help('Provide a detailed description that helps customers understand your product')
                ->select('product_type')
                    ->searchable()
                    ->sortable()
                    ->options([
                        'physical' => 'Physical Product',
                        'digital' => 'Digital Product',
                        'subscription' => 'Subscription Product'
                    ])
                    ->placeholder('Select product type')
                    ->help('Choose the type of product you are selling')
                    ->required()
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
                    ->help('Choose the most appropriate category for your product')
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
                    ->help('Select the brand or choose "Generic" for unbranded products')
                ->text('sku')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Stock Keeping Unit')
                    ->help('Unique identifier for inventory management (leave empty to auto-generate)')
                ->text('slug')
                    ->searchable()
                    ->sortable()
                    ->placeholder('product-url-slug')
                    ->help('URL-friendly version of the product name (e.g., "wireless-headphones")')
            ->end()

            // 2. Media & Images Tab
            ->tab('media', 'Media & Images', 'fa fa-image')
                ->file('image')
                    ->accept('image/*')->imageManager(true)
                    ->placeholder('Upload main product image')
                    ->help('Upload a high-quality image (800x800px recommended, max 2MB)')
                ->text('thumbnail')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Thumbnail URL or path')
                    ->help('Path to the thumbnail image (auto-generated if left empty)')
                ->customHtml('
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <div class="flex items-center">
                            <i class="fa fa-info-circle text-blue-500 mr-2"></i>
                            <span class="text-blue-800 text-sm">
                                <strong>Image Guidelines:</strong> Recommended size: 800x800px, Max file size: 2MB,
                                Supported formats: JPG, PNG, GIF, WebP
                            </span>
                        </div>
                    </div>
                ', 'Image Guidelines', 'mb-3')
            ->end()

            // 4. Inventory & Shipping Tab
            ->tab('inventory', 'Inventory & Shipping', 'fa fa-boxes')
                ->number('stock_quantity')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Available quantity')
                    ->help('Current available stock quantity (0 for out of stock)')
                    ->rules(['min:0', 'integer'])
                    ->showWhen('product_type', 'physical')
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
                    ->help('Current availability status of the product')
                    ->showWhen('product_type', 'physical')
                ->number('weight')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Weight in grams')
                    ->help('Product weight in grams (used for shipping calculations)')
                    ->rules(['min:0', 'numeric'])
                    ->showWhen('product_type', 'physical')
                ->text('dimensions')
                    ->searchable()
                    ->sortable()
                    ->placeholder('L x W x H (cm)')
                    ->help('Product dimensions in centimeters (e.g., "30 x 20 x 10")')
                    ->showWhen('product_type', 'physical')
                ->text('barcode')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Product barcode/UPC')
                    ->help('Product barcode or UPC code for scanning')
                    ->showWhen('product_type', 'physical')
                ->text('download_link')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Download URL or file path')
                    ->help('Direct download link for digital products')
                    ->showWhen('product_type', 'digital')
                ->text('file_size')
                    ->searchable()
                    ->sortable()
                    ->placeholder('File size (e.g., 15.2 MB)')
                    ->help('Size of the digital file')
                    ->showWhen('product_type', 'digital')
                ->text('shipping_class')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Shipping class (e.g., Standard, Express)')
                    ->help('Shipping class for physical products')
                    ->showWhen('product_type', 'physical')
                ->text('delivery_method')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Delivery method (e.g., Email, Download)')
                    ->help('How digital products are delivered')
                    ->showWhen('product_type', 'digital')
                ->text('access_url')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Access URL for digital products')
                    ->help('URL where customers can access digital products')
                    ->showWhen('product_type', 'digital')
                ->text('access_credentials')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Access credentials (username/password)')
                    ->help('Login credentials for digital product access')
                    ->showWhen('product_type', 'digital')
                ->text('subscription_access')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Subscription access details')
                    ->help('How customers access subscription products')
                    ->showWhen('product_type', 'subscription')
                ->select('subscription_duration')
                    ->searchable()
                    ->sortable()
                    ->options([
                        'unlimited' => 'Unlimited Duration',
                        '30_days' => '30 Days',
                        '90_days' => '90 Days',
                        '180_days' => '180 Days',
                        '365_days' => '1 Year',
                        'custom' => 'Custom Duration'
                    ])
                    ->placeholder('Select subscription duration')
                    ->help('Duration of subscription access')
                    ->showWhen('product_type', 'subscription')
            ->end()

            // 3. Pricing & Billing Tab
            ->tab('pricing', 'Pricing & Billing', 'fa fa-dollar-sign')
                ->number('price')
                    ->searchable()
                    ->sortable()
                    ->placeholder('0.00')
                    ->help('Enter the price in your local currency (e.g., 29.99)')
                    ->rules(['min:0', 'numeric'])
                    ->required()
                ->number('subscription_price')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Monthly subscription price')
                    ->help('Monthly subscription price (leave empty if not a subscription product)')
                    ->rules(['min:0', 'numeric'])
                    ->showWhen('product_type', 'subscription')
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
                    ->help('Billing frequency for subscription products')
                    ->showWhen('product_type', 'subscription')
                ->textarea('subscription_terms')
                    ->placeholder('Subscription terms and conditions')
                    ->height(100)
                    ->help('Terms and conditions for subscription products')
                    ->showWhen('product_type', 'subscription')
                ->number('shipping_cost')
                    ->searchable()
                    ->sortable()
                    ->placeholder('0.00')
                    ->help('Shipping cost for physical products (leave empty for free shipping)')
                    ->rules(['min:0', 'numeric'])
                    ->showWhen('product_type', 'physical')
                ->select('tax_rate')
                    ->searchable()
                    ->sortable()
                    ->options([
                        '0' => 'No Tax (0%)',
                        '5' => 'Low Tax (5%)',
                        '10' => 'Standard Tax (10%)',
                        '15' => 'High Tax (15%)',
                        '20' => 'Premium Tax (20%)'
                    ])
                    ->placeholder('Select tax rate')
                    ->help('Tax rate applied to this product')
                    ->showWhen('product_type', 'physical')
                ->text('license_key')
                    ->searchable()
                    ->sortable()
                    ->placeholder('License key or activation code')
                    ->help('License key for digital products (if applicable)')
                    ->showWhen('product_type', 'digital')
                ->select('license_type')
                    ->searchable()
                    ->sortable()
                    ->options([
                        'single_use' => 'Single Use License',
                        'multi_use' => 'Multi-Use License',
                        'unlimited' => 'Unlimited License',
                        'subscription' => 'Subscription License'
                    ])
                    ->placeholder('Select license type')
                    ->help('Type of license for digital products')
                    ->showWhen('product_type', 'digital')
            ->end()

            // 5. Product Settings Tab
            ->tab('settings', 'Product Settings', 'fa fa-cog')
                ->switch('is_active')
                    ->searchable()
                    ->sortable()
                    ->label('Product Active')
                    ->help('Enable this to make the product visible to customers')
                ->switch('is_featured')
                    ->searchable()
                    ->sortable()
                    ->label('Featured Product')
                    ->help('Featured products appear prominently on the homepage')
                ->switch('is_visible')
                    ->searchable()
                    ->sortable()
                    ->label('Visible to Customers')
                    ->help('Control whether customers can see this product')
                ->switch('is_featured_on_homepage')
                    ->searchable()
                    ->sortable()
                    ->label('Featured on Homepage')
                    ->help('Display this product in the homepage featured section')
                ->switch('is_new')
                    ->searchable()
                    ->sortable()
                    ->label('New Product')
                    ->help('Mark as new product (shows "New" badge)')
                ->switch('is_on_sale')
                    ->searchable()
                    ->sortable()
                    ->label('On Sale')
                    ->help('Mark as on sale (shows sale badge and pricing)')
                ->switch('is_taxable')
                    ->searchable()
                    ->sortable()
                    ->label('Taxable Product')
                    ->help('Apply sales tax to this product')
                ->switch('requires_shipping')
                    ->searchable()
                    ->sortable()
                    ->label('Requires Shipping')
                    ->help('Physical products that need to be shipped')
                    ->showWhen('product_type', 'physical')
                ->switch('is_downloadable')
                    ->searchable()
                    ->sortable()
                    ->label('Downloadable Product')
                    ->help('Digital products that can be downloaded')
                    ->showWhen('product_type', 'digital')
                ->switch('auto_renew')
                    ->searchable()
                    ->sortable()
                    ->label('Auto-Renew Subscription')
                    ->help('Automatically renew subscription when it expires')
                    ->showWhen('product_type', 'subscription')
                ->number('sort_order')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Display order (lower numbers first)')
                    ->help('Control the display order of products (lower numbers appear first)')
                    ->rules(['integer'])
            ->end()

            // 6. SEO & Marketing Tab
            ->tab('seo', 'SEO & Marketing', 'fa fa-search')
                ->text('meta_title')
                    ->searchable()
                    ->sortable()
                    ->placeholder('SEO title (max 60 characters)')
                    ->help('SEO title for search engines (keep under 60 characters for best results)')
                    ->rules(['max:60'])
                ->textarea('meta_description')
                    ->placeholder('SEO description (max 160 characters)')
                    ->height(100)
                    ->help('SEO description for search engines (keep under 160 characters)')
                    ->rules(['max:160'])
                ->text('meta_keywords')
                    ->searchable()
                    ->sortable()
                    ->placeholder('SEO keywords (comma separated)')
                    ->help('Comma-separated keywords for SEO (e.g., "wireless, headphones, bluetooth")')
            ->end()

            // 7. Additional Information Tab
            ->tab('additional', 'Additional Information', 'fa fa-plus-circle')
                ->text('vendor')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Product vendor/supplier')
                    ->help('Name of the vendor or supplier')
                ->text('warranty')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Warranty information')
                    ->help('Warranty details (e.g., "1 year limited warranty")')
                ->textarea('additional_info')
                    ->placeholder('Additional product information')
                    ->height(100)
                    ->help('Any additional information that might be useful to customers')
                ->textarea('custom_fields')
                    ->placeholder('Custom fields (JSON format)')
                    ->height(100)
                    ->help('Store additional custom data in JSON format')
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
