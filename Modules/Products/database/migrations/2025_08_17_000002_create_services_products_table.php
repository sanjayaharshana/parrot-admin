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
        Schema::create('services_products', function (Blueprint $table) {
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
            
            // Service Details
            $table->enum('service_type', ['consultation', 'training', 'maintenance', 'support', 'custom', 'other'])->default('consultation');
            $table->enum('duration_type', ['hourly', 'daily', 'weekly', 'monthly', 'project_based', 'one_time'])->default('hourly');
            $table->integer('duration_value')->nullable(); // number of hours/days/weeks/months
            $table->text('service_scope')->nullable();
            $table->text('deliverables')->nullable();
            
            // Pricing & Billing
            $table->enum('pricing_model', ['fixed', 'hourly', 'daily', 'monthly', 'project_based', 'subscription'])->default('fixed');
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->decimal('daily_rate', 10, 2)->nullable();
            $table->decimal('monthly_rate', 10, 2)->nullable();
            $table->decimal('setup_fee', 10, 2)->default(0.00);
            $table->decimal('cancellation_fee', 10, 2)->default(0.00);
            
            // Service Requirements
            $table->text('prerequisites')->nullable();
            $table->text('requirements')->nullable();
            $table->text('whats_included')->nullable();
            $table->text('whats_not_included')->nullable();
            $table->text('terms_conditions')->nullable();
            
            // Availability & Scheduling
            $table->boolean('requires_consultation')->default(false);
            $table->boolean('requires_quote')->default(false);
            $table->integer('lead_time_days')->nullable(); // days needed to start service
            $table->text('availability_schedule')->nullable();
            $table->text('timezone_requirements')->nullable();
            
            // Service Provider
            $table->string('service_provider')->nullable();
            $table->text('provider_credentials')->nullable();
            $table->text('provider_experience')->nullable();
            $table->text('provider_certifications')->nullable();
            
            // Quality & Guarantee
            $table->text('quality_guarantee')->nullable();
            $table->text('satisfaction_guarantee')->nullable();
            $table->integer('warranty_days')->nullable();
            $table->text('refund_policy')->nullable();
            
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
            $table->index('service_type');
            $table->index('pricing_model');
            $table->index('duration_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services_products');
    }
};
