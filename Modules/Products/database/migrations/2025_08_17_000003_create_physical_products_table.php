<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('physical_products', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('sku')->unique();
            $table->string('slug')->unique();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->string('vendor')->nullable();
            
            // Status & Settings
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_featured_on_homepage')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('is_on_sale')->default(false);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_taxable')->default(true);
            
            // Media
            $table->string('image')->nullable();
            $table->string('thumbnail')->nullable();
            
            // Inventory & Stock
            $table->integer('stock_quantity')->default(0);
            $table->enum('stock_status', ['in_stock', 'low_stock', 'out_of_stock'])->default('in_stock');
            $table->integer('low_stock_threshold')->default(10);
            $table->boolean('track_inventory')->default(true);
            $table->boolean('allow_backorders')->default(false);
            $table->integer('max_backorder_quantity')->nullable();
            
            // Physical Properties
            $table->decimal('weight', 8, 2)->nullable(); // in kg
            $table->string('weight_unit')->default('kg');
            $table->string('dimensions')->nullable(); // LxWxH in cm
            $table->string('dimension_unit')->default('cm');
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->string('material')->nullable();
            $table->string('model_number')->nullable();
            $table->string('part_number')->nullable();
            
            // Shipping & Handling
            $table->decimal('shipping_cost', 8, 2)->default(0.00);
            $table->boolean('requires_shipping')->default(true);
            $table->string('shipping_class')->nullable();
            $table->string('delivery_method')->nullable();
            $table->integer('handling_time_days')->default(1);
            $table->boolean('free_shipping')->default(false);
            $table->decimal('free_shipping_threshold', 10, 2)->nullable();
            
            // Product Identification
            $table->string('barcode')->nullable();
            $table->string('upc')->nullable();
            $table->string('ean')->nullable();
            $table->string('isbn')->nullable();
            $table->string('gtin')->nullable();
            
            // Warranty & Returns
            $table->string('warranty')->nullable();
            $table->integer('return_days')->default(30);
            $table->text('return_policy')->nullable();
            $table->boolean('refundable')->default(true);
            $table->boolean('exchangeable')->default(true);
            
            // Assembly & Installation
            $table->boolean('requires_assembly')->default(false);
            $table->text('assembly_instructions')->nullable();
            $table->boolean('requires_installation')->default(false);
            $table->text('installation_instructions')->nullable();
            $table->boolean('includes_tools')->default(false);
            $table->text('included_items')->nullable();
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            
            // Additional Info
            $table->text('additional_info')->nullable();
            $table->json('custom_fields')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['is_active', 'is_visible']);
            $table->index(['category', 'is_active']);
            $table->index(['brand', 'is_active']);
            $table->index('is_featured');
            $table->index('is_on_sale');
            $table->index('stock_status');
            $table->index('stock_quantity');
            $table->index('requires_shipping');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('physical_products');
    }
};
