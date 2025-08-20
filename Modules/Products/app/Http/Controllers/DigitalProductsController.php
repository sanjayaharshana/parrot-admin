<?php

namespace Modules\Products\Http\Controllers;

use Modules\Products\Models\DigitalProduct;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\ResourceController;
use Modules\UserPanel\Services\ResourceService;

class DigitalProductsController extends ResourceController
{
    public $icon = 'fa fa-download';
    public $model = DigitalProduct::class;
    public $routeName = 'digital-products';
    public $parentMenu = 'Products';

        protected function makeResource(): ResourceService
    {
        return (new ResourceService(DigitalProduct::class, 'digital-products'))
            ->title('Digital Products Management')
            ->description('Manage digital products, downloads, and digital assets')
            ->enableTabs()

            // 1. Basic Information Tab - Core product details
            ->tab('basic', 'Basic Information', 'fa fa-info-circle')
                ->text('name')->required()->placeholder('Enter product name')->help('Product name is required')
                ->textarea('description')->placeholder('Enter product description')->help('Detailed description of the digital product')
                ->text('sku')->required()->placeholder('Enter SKU')->help('Stock Keeping Unit - must be unique')->rules([
                'unique:digital_products,sku'
            ])
                ->text('slug')->placeholder('Enter URL slug')->help('URL-friendly version of the name')->required()->rules([
                    'unique:digital_products,slug',
            ])
                ->number('price')->required()->placeholder('0.00')->help('Product price in decimal format')
                ->text('category')->placeholder('Enter product category')->help('Product category for organization')
                ->text('brand')->placeholder('Enter brand name')->help('Product brand or manufacturer')
                ->text('vendor')->placeholder('Enter vendor name')->help('Product vendor or supplier')
                ->number('sort_order')->placeholder('0')->help('Display order (lower numbers first)')->required()
            ->end()

            // 2. Media & Files Tab - Images, downloads, file management
            ->tab('media', 'Media & Files', 'fa fa-file-image-o')
                ->file('image')->imageManager()
                ->file('thumbnail')->imageManager()
                ->url('download_link')->placeholder('https://example.com/file.zip')->help('Direct download URL for the digital product')
                ->text('file_path')->placeholder('/storage/digital-products/')->help('Local file path on server')
                ->text('file_name')->placeholder('product-file.zip')->help('Original file name')
                ->text('file_extension')->placeholder('zip')->help('File extension (zip, pdf, exe, etc.)')
                ->number('file_size')->placeholder('1024')->help('File size in bytes (leave empty for auto-calculation)')
                ->text('file_size_formatted')->placeholder('1.0 MB')->help('Human readable file size')
                ->number('download_limit')->placeholder('5')->help('Number of downloads allowed (leave empty for unlimited)')
                ->number('download_expiry_days')->placeholder('30')->help('Days until download expires (leave empty for never)')
            ->end()

            // 3. Settings & Rights Tab - Status, licensing, access control
            ->tab('settings', 'Settings & Rights', 'fa fa-cog')
                ->switch('is_active')->help('Make this product visible and purchasable')
                ->switch('is_featured')->help('Highlight this product as featured')
                ->switch('is_visible')->help('Show this product in catalog')
                ->switch('is_featured_on_homepage')->help('Display on homepage carousel')
                ->switch('is_new')->help('Mark as new product')
                ->switch('is_on_sale')->help('Mark as on sale')
                ->switch('is_taxable')->help('Apply taxes to this product')
                ->select('license_type', ['personal', 'commercial', 'extended'])->placeholder('Select license type')->help('Type of license for the digital product')
                ->textarea('license_terms')->placeholder('Enter license terms and conditions')->help('Detailed license terms and conditions')
                ->text('usage_rights')->placeholder('Personal use only')->help('Usage rights description')
                ->switch('allow_resale')->help('Allow customers to resell this product')
                ->switch('allow_modification')->help('Allow customers to modify this product')
                ->switch('requires_login')->help('Require user authentication to download')
                ->switch('instant_download')->help('Allow immediate download after purchase')
            ->end()

            // 4. Technical Details Tab - Compatibility, versioning, requirements
            ->tab('technical', 'Technical Details', 'fa fa-desktop')
                ->text('compatible_platforms')->placeholder('Windows 10+, macOS 10.15+, Linux Ubuntu 18.04+')->help('Supported platforms (Windows, Mac, Linux)')
                ->text('compatible_software')->placeholder('Chrome 90+, Firefox 88+')->help('Required software or browsers')
                ->textarea('minimum_requirements')->placeholder('Minimum system requirements')->help('Minimum system requirements for the product')
                ->textarea('recommended_requirements')->placeholder('Recommended system requirements')->help('Recommended system requirements for optimal performance')
                ->text('version')->placeholder('1.0.0')->help('Current version number (semantic versioning)')
                ->date('release_date')->help('Release date of this version')
                ->switch('auto_updates')->help('Enable automatic updates for this product')
                ->textarea('update_notes')->placeholder('What\'s new in this version')->help('Update notes and changelog')
                ->url('access_url')->placeholder('https://app.example.com/access')->help('Access URL for online content or web app')
                ->text('access_credentials')->placeholder('username:password')->help('Access credentials (hidden from public view)')
                ->textarea('access_instructions')->placeholder('Step-by-step access instructions')->help('Instructions for accessing the content')
                ->select('delivery_method', ['download', 'email', 'access_link'])->placeholder('Select delivery method')->help('How the digital content is delivered to customers')
            ->end()

            // 5. SEO & Additional Tab - Meta data, custom fields, extra info
            ->tab('seo', 'SEO & Additional', 'fa fa-search')
                ->text('meta_title')->placeholder('Enter meta title for SEO')->help('Meta title for search engines (60 characters max)')
                ->textarea('meta_description')->placeholder('Enter meta description for SEO')->help('Meta description for search engines (160 characters max)')
                ->text('meta_keywords')->placeholder('digital, software, download, license')->help('Comma-separated keywords for SEO')
                ->textarea('additional_info')->placeholder('Additional product information')->help('Any additional information about the product')
                ->textarea('custom_fields')->placeholder('{"field1": "value1", "field2": "value2"}')->help('Custom fields in JSON format for additional data')
                ->url('preview_url')->placeholder('https://preview.example.com/product')->help('URL where customers can preview the product')
                ->url('demo_url')->placeholder('https://demo.example.com/product')->help('URL where customers can try a demo')
                ->switch('has_preview')->help('Enable if preview is available')
                ->switch('has_demo')->help('Enable if demo is available')
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
        $dataView = new \Modules\UserPanel\Services\DataViewService(new DigitalProduct());

        $dataView->title('Digital Products Management')
            ->description('Manage digital products, downloads, and digital assets')
            ->routePrefix('digital-products')
            ->perPage(15)
            ->defaultSort('id', 'desc')
            ->pagination(true)
            ->search(true);

        // ID column
        $dataView->id('ID')->sortable();

        // Basic Information
        $dataView->column('name', 'Name')
            ->sortable()
            ->searchable();

        $dataView->column('sku', 'SKU')
            ->sortable()
            ->searchable();

        $dataView->column('price', 'Price')
            ->sortable()
            ->searchable();

        $dataView->column('category', 'Category')
            ->sortable()
            ->searchable();

        $dataView->column('brand', 'Brand')
            ->sortable()
            ->searchable();

        // Status
        $dataView->column('is_active', 'Status')
            ->sortable()
            ->searchable();

        $dataView->column('is_featured', 'Featured')
            ->sortable()
            ->searchable();

        // File Information
        $dataView->column('file_size_formatted', 'File Size')
            ->sortable()
            ->searchable();

        $dataView->column('license_type', 'License')
            ->sortable()
            ->searchable();


        $dataView->column('delivery_method', 'Delivery')
            ->sortable()
            ->searchable();

        // Download Settings
        $dataView->column('requires_login', 'Auth Required')
            ->sortable()
            ->searchable();

        $dataView->column('instant_download', 'Instant Download')
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
        $dataView->createButton(route('digital-products.create'), 'Create New Digital Product');

        return $dataView;
    }

    /**
     * Get validation rules for the request
     */
    protected function getValidationRules($request = null, $id = null)
    {
        $rules = [
            // Basic Information
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:digital_products,sku',
            'slug' => 'nullable|string|max:255|unique:digital_products,slug',
            'price' => 'required|numeric|min:0|max:999999.99',
            'category' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'vendor' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0|max:999999',

            // Media & Files
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:1024',
            'download_link' => 'nullable|url|max:500',
            'file_path' => 'nullable|string|max:500',
            'file_name' => 'nullable|string|max:255',
            'file_extension' => 'nullable|string|max:50',
            'file_size' => 'nullable|integer|min:0',
            'file_size_formatted' => 'nullable|string|max:50',
            'download_limit' => 'nullable|integer|min:0|max:999999',
            'download_expiry_days' => 'nullable|integer|min:0|max:3650',

            // Settings & Rights
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_visible' => 'boolean',
            'is_featured_on_homepage' => 'boolean',
            'is_new' => 'boolean',
            'is_on_sale' => 'boolean',
            'is_taxable' => 'boolean',
            'license_type' => 'nullable|in:personal,commercial,extended',
            'license_terms' => 'nullable|string|max:1000',
            'usage_rights' => 'nullable|string|max:500',
            'allow_resale' => 'boolean',
            'allow_modification' => 'boolean',
            'requires_login' => 'boolean',
            'instant_download' => 'boolean',

            // Technical Details
            'compatible_platforms' => 'nullable|string|max:500',
            'compatible_software' => 'nullable|string|max:500',
            'minimum_requirements' => 'nullable|string|max:1000',
            'recommended_requirements' => 'nullable|string|max:1000',
            'version' => 'nullable|string|max:50',
            'release_date' => 'nullable|date',
            'auto_updates' => 'boolean',
            'update_notes' => 'nullable|string|max:1000',
            'access_url' => 'nullable|url|max:500',
            'access_credentials' => 'nullable|string|max:500',
            'access_instructions' => 'nullable|string|max:1000',
            'delivery_method' => 'nullable|in:download,email,access_link',

            // SEO & Additional
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:500',
            'additional_info' => 'nullable|string|max:2000',
            'custom_fields' => 'nullable|string|max:2000',
            'preview_url' => 'nullable|url|max:500',
            'demo_url' => 'nullable|url|max:500',
            'has_preview' => 'boolean',
            'has_demo' => 'boolean',
        ];

        return $rules;
    }

    /**
     * Get custom validation messages
     */
    protected function getValidationMessages()
    {
        return [
            'name.required' => 'Product name is required.',
            'sku.required' => 'SKU is required.',
            'sku.unique' => 'This SKU is already in use.',
            'price.required' => 'Product price is required.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price cannot be negative.',
            'image.image' => 'The image must be a valid image file.',
            'image.mimes' => 'The image must be a JPEG, PNG, JPG, GIF, or WebP file.',
            'image.max' => 'The image size cannot exceed 2MB.',
            'thumbnail.max' => 'The thumbnail size cannot exceed 1MB.',
            'download_link.url' => 'Download link must be a valid URL.',
            'access_url.url' => 'Access URL must be a valid URL.',
            'preview_url.url' => 'Preview URL must be a valid URL.',
            'demo_url.url' => 'Demo URL must be a valid URL.',
            'meta_title.max' => 'Meta title cannot exceed 60 characters.',
            'meta_description.max' => 'Meta description cannot exceed 160 characters.',
        ];
    }

        /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return parent::store($request);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        return parent::update($request, $id);
    }
}
