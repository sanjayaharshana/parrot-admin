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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('sku')->unique();
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            $table->string('image')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->string('barcode')->nullable();
            $table->string('weight')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('stock_status')->default('in_stock');
            $table->integer('stock_quantity')->default(0);
            $table->boolean('is_taxable')->default(true);
            $table->boolean('is_digital')->default(false);
            $table->boolean('is_subscription')->default(false);
            $table->decimal('subscription_price', 10, 2)->nullable();
            $table->string('subscription_interval')->nullable(); // e.g., 'monthly', 'yearly'
            $table->text('subscription_terms')->nullable();
            $table->string('vendor')->nullable();
            $table->string('warranty')->nullable();
            $table->text('additional_info')->nullable();
            $table->text('custom_fields')->nullable(); // JSON for custom fields
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_featured_on_homepage')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('is_on_sale')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
