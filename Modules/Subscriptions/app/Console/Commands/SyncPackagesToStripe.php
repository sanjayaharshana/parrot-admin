<?php

namespace Modules\Subscriptions\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Laravel\Cashier\Cashier;
use Modules\Subscriptions\Models\Plan;
use Modules\Subscriptions\Models\PlanPrice;
use Stripe\StripeClient;

class SyncPackagesToStripe extends Command
{
    protected $signature = 'subscription:package-sync {--dry-run : Show actions without making changes}';

    protected $description = 'Sync local subscription plans and prices to Stripe, creating missing products/prices and updating local DB with Stripe IDs.';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $stripe = null;
        $secret = config('services.stripe.secret');
        if (! $dryRun) {
            if (empty($secret)) {
                $this->error('Stripe secret key not configured. Set STRIPE_SECRET in .env');
                return self::FAILURE;
            }
            $stripe = new StripeClient($secret);
        }

        $plans = Plan::with('prices')->get();
        foreach ($plans as $plan) {
            $this->info("Plan: {$plan->name}");

            // Ensure Stripe product exists
            if (!$plan->stripe_product_id) {
                if ($dryRun) {
                    $this->line('  - Would create Stripe product');
                } else {
                    $product = $stripe->products->create([
                        'name' => $plan->name,
                        'active' => (bool) $plan->is_active,
                        'metadata' => [
                            'local_plan_id' => (string) $plan->id,
                            'slug' => $plan->slug,
                        ],
                    ]);
                    $plan->stripe_product_id = $product->id;
                    $plan->save();
                    $this->line("  - Created product {$product->id}");
                }
            } else {
                $this->line("  - Product: {$plan->stripe_product_id}");
            }

            foreach ($plan->prices as $price) {
                $label = strtoupper($price->currency).' '.number_format($price->amount/100, 2).' / '.$price->interval;
                if (!$price->stripe_price_id) {
                    if ($dryRun) {
                        $this->line("    - Would create Stripe price: {$label}");
                    } else {
                        if (!$plan->stripe_product_id) {
                            $this->error('    - Missing stripe_product_id on plan; cannot create price.');
                            continue;
                        }
                        $created = $stripe->prices->create([
                            'currency' => strtolower($price->currency),
                            'unit_amount' => $price->amount,
                            'recurring' => [
                                'interval' => $price->interval,
                            ],
                            'product' => $plan->stripe_product_id,
                            'active' => (bool) $price->active,
                            'metadata' => [
                                'local_plan_id' => (string) $plan->id,
                                'local_price_id' => (string) $price->id,
                            ],
                        ]);
                        $price->stripe_price_id = $created->id;
                        $price->save();
                        $this->line("    - Created price {$created->id} ({$label})");
                    }
                } else {
                    $this->line("    - Price: {$price->stripe_price_id} ({$label})");
                }
            }
        }

        $this->info('Sync complete.');
        return self::SUCCESS;
    }
}


