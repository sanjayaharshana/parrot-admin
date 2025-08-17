<?php

/**
 * Smart Tab Organization Demo
 * 
 * This script demonstrates the new smart tab organization system
 * implemented in the Products module.
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use Modules\UserPanel\Services\ResourceService;
use Modules\Products\Models\Products;

echo "🚀 Smart Tab Organization Demo\n";
echo "===============================\n\n";

// Create a new resource service for products
$resource = new ResourceService(Products::class, 'products');

echo "📋 Setting up Product Resource with Smart Tabs...\n\n";

// Enable tabs
$resource->enableTabs();

// 1. Essential Information Tab (High Priority - Shows First)
echo "⭐ Creating Essential Info Tab (High Priority)...\n";
$resource->tab('essential', 'Essential Information', 'fa fa-star')
    ->priority('high')  // Always show first
    ->fields(['name', 'product_type', 'category', 'sku', 'is_active'])
    ->end();

// 2. Product Details Tab (Medium Priority)
echo "ℹ️  Creating Product Details Tab (Medium Priority)...\n";
$resource->tab('details', 'Product Details', 'fa fa-info-circle')
    ->priority('medium')
    ->fields(['description', 'brand', 'slug', 'vendor', 'warranty'])
    ->end();

// 3. Media & Images Tab (Medium Priority)
echo "🖼️  Creating Media Tab (Medium Priority)...\n";
$resource->tab('media', 'Media & Images', 'fa fa-image')
    ->priority('medium')
    ->fields(['image', 'thumbnail'])
    ->end();

// 4. Pricing & Billing Tab (Medium Priority)
echo "💰 Creating Pricing Tab (Medium Priority)...\n";
$resource->tab('pricing', 'Pricing & Billing', 'fa fa-dollar-sign')
    ->priority('medium')
    ->fields(['price', 'subscription_price', 'shipping_cost', 'tax_rate'])
    ->end();

// 5. Inventory & Shipping Tab (Medium Priority)
echo "📦 Creating Inventory Tab (Medium Priority)...\n";
$resource->tab('inventory', 'Inventory & Shipping', 'fa fa-boxes')
    ->priority('medium')
    ->fields(['stock_quantity', 'stock_status', 'weight', 'dimensions', 'barcode'])
    ->end();

// 6. Product Settings Tab (Medium Priority)
echo "⚙️  Creating Settings Tab (Medium Priority)...\n";
$resource->tab('settings', 'Product Settings', 'fa fa-cog')
    ->priority('medium')
    ->fields(['is_featured', 'is_visible', 'is_featured_on_homepage', 'is_new', 'is_on_sale', 'is_taxable', 'requires_shipping', 'is_downloadable', 'sort_order'])
    ->end();

// 7. Digital & Subscription Tab (Low Priority, Collapsible)
echo "🔑 Creating Digital Tab (Low Priority, Collapsible)...\n";
$resource->tab('digital', 'Digital & Subscription', 'fa fa-key')
    ->priority('low')   // Show last
    ->collapsible()     // Can be collapsed
    ->fields(['download_link', 'file_size', 'access_url', 'access_credentials', 'subscription_interval', 'subscription_duration', 'subscription_terms', 'auto_renew', 'license_key', 'license_type'])
    ->end();

// 8. SEO & Marketing Tab (Low Priority, Collapsible)
echo "🔍 Creating SEO Tab (Low Priority, Collapsible)...\n";
$resource->tab('seo', 'SEO & Marketing', 'fa fa-search')
    ->priority('low')   // Show last
    ->collapsible()     // Can be collapsed
    ->fields(['meta_title', 'meta_description', 'meta_keywords'])
    ->end();

// 9. Additional Information Tab (Low Priority, Collapsible)
echo "➕ Creating Additional Info Tab (Low Priority, Collapsible)...\n";
$resource->tab('additional', 'Additional Information', 'fa fa-plus-circle')
    ->priority('low')   // Show last
    ->collapsible()     // Can be collapsed
    ->fields(['additional_info', 'custom_fields', 'shipping_class', 'delivery_method'])
    ->end();

echo "\n✅ All tabs created successfully!\n\n";

// Demonstrate tab ordering by priority
echo "📊 Tab Order by Priority:\n";
echo "========================\n";

$orderedTabs = $resource->getTabsOrderedByPriority();
$tabNumber = 1;

foreach ($orderedTabs as $tabId => $tab) {
    $priority = $tab['priority'] ?? 'medium';
    $collapsible = $tab['collapsible'] ?? false ? ' (Collapsible)' : '';
    $icon = $tab['icon'] ?? 'fa fa-info';
    
    echo sprintf(
        "%d. %s %s - %s%s\n",
        $tabNumber++,
        $icon,
        $tab['label'],
        ucfirst($priority) . ' Priority',
        $collapsible
    );
}

echo "\n🎯 Key Features Demonstrated:\n";
echo "=============================\n";
echo "• High Priority tabs show first (Essential Information)\n";
echo "• Medium Priority tabs show in middle (Details, Media, Pricing, etc.)\n";
echo "• Low Priority tabs show last (Digital, SEO, Additional Info)\n";
echo "• Collapsible tabs reduce visual clutter\n";
echo "• Automatic field organization within tabs\n";
echo "• Priority-based visual styling\n";

echo "\n🚀 The smart tab organization system is now ready to use!\n";
echo "You can access this in your admin panel at /products/create or /products/edit\n\n";

// Show tab metadata
echo "📋 Tab Metadata:\n";
echo "================\n";
foreach ($orderedTabs as $tabId => $tab) {
    echo sprintf(
        "Tab: %s\n  - Priority: %s\n  - Collapsible: %s\n  - Fields: %d\n\n",
        $tab['label'],
        $tab['priority'] ?? 'medium',
        $tab['collapsible'] ?? false ? 'Yes' : 'No',
        count($tab['fields'] ?? [])
    );
}
