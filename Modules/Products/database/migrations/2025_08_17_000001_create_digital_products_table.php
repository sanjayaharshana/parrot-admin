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
        Schema::create('digital_products', function (Blueprint $table) {
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
            
            // File Information
            $table->string('download_link')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_extension')->nullable();
            $table->bigInteger('file_size')->nullable(); // in bytes
            $table->string('file_size_formatted')->nullable(); // e.g., "2.5 MB"
            
            // Download Settings
            $table->integer('download_limit')->nullable(); // null = unlimited
            $table->integer('download_expiry_days')->nullable(); // null = never expires
            $table->boolean('requires_login')->default(true);
            $table->boolean('instant_download')->default(true);
            
            // Digital Rights
            $table->string('license_type')->nullable(); // personal, commercial, extended
            $table->text('license_terms')->nullable();
            $table->string('usage_rights')->nullable();
            $table->boolean('allow_resale')->default(false);
            $table->boolean('allow_modification')->default(false);
            
            // Access & Delivery
            $table->string('access_url')->nullable();
            $table->string('access_credentials')->nullable();
            $table->text('access_instructions')->nullable();
            $table->enum('delivery_method', ['download', 'email', 'access_link'])->default('download');
            
            // Compatibility
            $table->string('compatible_platforms')->nullable(); // Windows, Mac, Linux, etc.
            $table->string('compatible_software')->nullable();
            $table->string('minimum_requirements')->nullable();
            $table->string('recommended_requirements')->nullable();
            
            // Version & Updates
            $table->string('version')->nullable();
            $table->date('release_date')->nullable();
            $table->boolean('auto_updates')->default(false);
            $table->text('update_notes')->nullable();
            
            // Preview & Demo
            $table->string('preview_url')->nullable();
            $table->string('demo_url')->nullable();
            $table->boolean('has_preview')->default(false);
            $table->boolean('has_demo')->default(false);
            
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
            $table->index('license_type');
            $table->index('delivery_method');
            $table->index('requires_login');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_products');
    }
};
