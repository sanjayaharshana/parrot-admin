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
        Schema::create('subscription_products', function (Blueprint $table) {
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
            
            // Subscription Pricing
            $table->decimal('subscription_price', 10, 2)->nullable();
            $table->enum('subscription_interval', ['daily', 'weekly', 'monthly', 'quarterly', 'yearly'])->default('monthly');
            $table->integer('billing_cycle')->default(1); // number of intervals
            $table->decimal('setup_fee', 8, 2)->default(0.00);
            $table->decimal('cancellation_fee', 8, 2)->default(0.00);
            $table->decimal('upgrade_fee', 8, 2)->default(0.00);
            $table->decimal('downgrade_fee', 8, 2)->default(0.00);
            
            // Subscription Terms
            $table->text('subscription_terms')->nullable();
            $table->integer('minimum_subscription_period')->nullable(); // in days
            $table->integer('trial_period_days')->default(0);
            $table->boolean('free_trial')->default(false);
            $table->boolean('auto_renew')->default(true);
            $table->boolean('prorate_changes')->default(true);
            
            // Access & Features
            $table->string('access_url')->nullable();
            $table->string('access_credentials')->nullable();
            $table->text('access_instructions')->nullable();
            $table->json('included_features')->nullable();
            $table->json('excluded_features')->nullable();
            $table->text('feature_description')->nullable();
            
            // Subscription Management
            $table->boolean('allow_upgrade')->default(true);
            $table->boolean('allow_downgrade')->default(true);
            $table->boolean('allow_pause')->default(false);
            $table->integer('pause_limit_days')->nullable();
            $table->boolean('allow_cancellation')->default(true);
            $table->boolean('allow_multiple_subscriptions')->default(false);
            
            // Content & Updates
            $table->enum('content_update_frequency', ['daily', 'weekly', 'monthly', 'quarterly', 'yearly'])->nullable();
            $table->text('content_description')->nullable();
            $table->boolean('includes_updates')->default(true);
            $table->boolean('includes_support')->default(true);
            $table->text('update_schedule')->nullable();
            
            // Limits & Restrictions
            $table->integer('user_limit')->nullable(); // null = unlimited
            $table->integer('device_limit')->nullable(); // null = unlimited
            $table->integer('storage_limit_mb')->nullable(); // null = unlimited
            $table->string('usage_restrictions')->nullable();
            $table->integer('concurrent_sessions')->nullable();
            $table->text('geographic_restrictions')->nullable();
            
            // Billing & Payment
            $table->enum('billing_method', ['recurring', 'prepaid', 'usage_based'])->default('recurring');
            $table->integer('grace_period_days')->default(7);
            $table->text('billing_notes')->nullable();
            $table->boolean('send_invoice')->default(true);
            $table->boolean('auto_payment')->default(true);
            
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
            $table->index('subscription_interval');
            $table->index('auto_renew');
            $table->index('free_trial');
            $table->index('billing_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_products');
    }
};
