<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('subscription_features', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key')->unique();
            $table->string('unit')->nullable();
            $table->boolean('is_metered')->default(false);
            $table->timestamps();
        });

        Schema::create('plan_feature', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_feature_id')->constrained()->cascadeOnDelete();
            $table->integer('limit')->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
            $table->unique(['subscription_plan_id','subscription_feature_id'], 'plan_feature_unique');
        });

        Schema::create('subscription_plan_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_plan_id')->constrained()->cascadeOnDelete();
            $table->string('interval'); // month, year
            $table->string('currency', 3)->default('USD');
            $table->integer('amount'); // in cents
            $table->string('stripe_price_id')->nullable()->index();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->unique(['subscription_plan_id','interval','currency'], 'plan_interval_currency_unique');
        });

        Schema::create('user_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_id')->index(); // Stripe invoice ID
            $table->string('status')->nullable();
            $table->integer('amount_due')->nullable();
            $table->integer('amount_paid')->nullable();
            $table->string('currency', 3)->nullable();
            $table->timestamp('due_date')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
            $table->unique(['user_id','invoice_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_invoices');
        Schema::dropIfExists('subscription_plan_prices');
        Schema::dropIfExists('plan_feature');
        Schema::dropIfExists('subscription_features');
        Schema::dropIfExists('subscription_plans');
    }
};


