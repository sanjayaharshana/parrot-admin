<?php

namespace Modules\Subscriptions\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Subscriptions\Models\Feature;
use Modules\Subscriptions\Models\Plan;
use Modules\Subscriptions\Models\PlanPrice;

class SubscriptionsDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Starter Plan
        $starter = Plan::firstOrCreate(
            ['slug' => 'starter'],
            [
                'name' => 'Starter',
                'description' => 'Perfect for small teams and startups getting started with our platform. Essential features to help you grow your business.',
                'is_active' => true
            ]
        );

        // Create Professional Plan
        $professional = Plan::firstOrCreate(
            ['slug' => 'professional'],
            [
                'name' => 'Professional',
                'description' => 'Advanced features for growing businesses. Everything you need to scale your operations and team.',
                'is_active' => true
            ]
        );

        // Create Enterprise Plan
        $enterprise = Plan::firstOrCreate(
            ['slug' => 'enterprise'],
            [
                'name' => 'Enterprise',
                'description' => 'Full-featured solution for large organizations. Advanced security, priority support, and custom integrations.',
                'is_active' => true
            ]
        );

        // Define features
        $features = [
            ['name' => 'Users', 'key' => 'users', 'unit' => 'count', 'is_metered' => false],
            ['name' => 'Projects', 'key' => 'projects', 'unit' => 'count', 'is_metered' => false],
            ['name' => 'Storage', 'key' => 'storage', 'unit' => 'GB', 'is_metered' => false],
            ['name' => 'API Calls', 'key' => 'api_calls', 'unit' => 'per month', 'is_metered' => false],
            ['name' => 'Priority Support', 'key' => 'priority_support', 'unit' => null, 'is_metered' => false],
            ['name' => 'Custom Integrations', 'key' => 'custom_integrations', 'unit' => null, 'is_metered' => false],
            ['name' => 'Advanced Analytics', 'key' => 'advanced_analytics', 'unit' => null, 'is_metered' => false],
            ['name' => 'White Label', 'key' => 'white_label', 'unit' => null, 'is_metered' => false],
        ];

        // Create features
        foreach ($features as $f) {
            $feature = Feature::firstOrCreate(['key' => $f['key']], $f);
        }

        // Attach features to Starter plan
        $starter->features()->syncWithoutDetaching([
            Feature::where('key', 'users')->first()->id => ['limit' => 5, 'enabled' => true],
            Feature::where('key', 'projects')->first()->id => ['limit' => 10, 'enabled' => true],
            Feature::where('key', 'storage')->first()->id => ['limit' => 50, 'enabled' => true],
            Feature::where('key', 'api_calls')->first()->id => ['limit' => 1000, 'enabled' => true],
            Feature::where('key', 'priority_support')->first()->id => ['limit' => null, 'enabled' => false],
            Feature::where('key', 'custom_integrations')->first()->id => ['limit' => null, 'enabled' => false],
            Feature::where('key', 'advanced_analytics')->first()->id => ['limit' => null, 'enabled' => false],
            Feature::where('key', 'white_label')->first()->id => ['limit' => null, 'enabled' => false],
        ]);

        // Attach features to Professional plan
        $professional->features()->syncWithoutDetaching([
            Feature::where('key', 'users')->first()->id => ['limit' => 25, 'enabled' => true],
            Feature::where('key', 'projects')->first()->id => ['limit' => 100, 'enabled' => true],
            Feature::where('key', 'storage')->first()->id => ['limit' => 500, 'enabled' => true],
            Feature::where('key', 'api_calls')->first()->id => ['limit' => 10000, 'enabled' => true],
            Feature::where('key', 'priority_support')->first()->id => ['limit' => null, 'enabled' => true],
            Feature::where('key', 'custom_integrations')->first()->id => ['limit' => 5, 'enabled' => true],
            Feature::where('key', 'advanced_analytics')->first()->id => ['limit' => null, 'enabled' => true],
            Feature::where('key', 'white_label')->first()->id => ['limit' => null, 'enabled' => false],
        ]);

        // Attach features to Enterprise plan
        $enterprise->features()->syncWithoutDetaching([
            Feature::where('key', 'users')->first()->id => ['limit' => null, 'enabled' => true],
            Feature::where('key', 'projects')->first()->id => ['limit' => null, 'enabled' => true],
            Feature::where('key', 'storage')->first()->id => ['limit' => null, 'enabled' => true],
            Feature::where('key', 'api_calls')->first()->id => ['limit' => null, 'enabled' => true],
            Feature::where('key', 'priority_support')->first()->id => ['limit' => null, 'enabled' => true],
            Feature::where('key', 'custom_integrations')->first()->id => ['limit' => null, 'enabled' => true],
            Feature::where('key', 'advanced_analytics')->first()->id => ['limit' => null, 'enabled' => true],
            Feature::where('key', 'white_label')->first()->id => ['limit' => null, 'enabled' => true],
        ]);

        // Create prices for Starter plan
        PlanPrice::updateOrCreate(
            ['subscription_plan_id' => $starter->id, 'interval' => 'month', 'currency' => 'USD'],
            [
                'amount' => 2900, // $29.00
                'stripe_price_id' => env('STRIPE_PRICE_STARTER_MONTHLY'),
                'active' => true,
            ]
        );

        PlanPrice::updateOrCreate(
            ['subscription_plan_id' => $starter->id, 'interval' => 'year', 'currency' => 'USD'],
            [
                'amount' => 29000, // $290.00 (2 months free)
                'stripe_price_id' => env('STRIPE_PRICE_STARTER_YEARLY'),
                'active' => true,
            ]
        );

        // Create prices for Professional plan
        PlanPrice::updateOrCreate(
            ['subscription_plan_id' => $professional->id, 'interval' => 'month', 'currency' => 'USD'],
            [
                'amount' => 7900, // $79.00
                'stripe_price_id' => env('STRIPE_PRICE_PROFESSIONAL_MONTHLY'),
                'active' => true,
            ]
        );

        PlanPrice::updateOrCreate(
            ['subscription_plan_id' => $professional->id, 'interval' => 'year', 'currency' => 'USD'],
            [
                'amount' => 79000, // $790.00 (2 months free)
                'stripe_price_id' => env('STRIPE_PRICE_PROFESSIONAL_YEARLY'),
                'active' => true,
            ]
        );

        // Create prices for Enterprise plan
        PlanPrice::updateOrCreate(
            ['subscription_plan_id' => $enterprise->id, 'interval' => 'month', 'currency' => 'USD'],
            [
                'amount' => 19900, // $199.00
                'stripe_price_id' => env('STRIPE_PRICE_ENTERPRISE_MONTHLY'),
                'active' => true,
            ]
        );

        PlanPrice::updateOrCreate(
            ['subscription_plan_id' => $enterprise->id, 'interval' => 'year', 'currency' => 'USD'],
            [
                'amount' => 199000, // $1990.00 (2 months free)
                'stripe_price_id' => env('STRIPE_PRICE_ENTERPRISE_YEARLY'),
                'active' => true,
            ]
        );
    }
}
