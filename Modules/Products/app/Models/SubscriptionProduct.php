<?php

namespace Modules\Products\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SubscriptionProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'sku',
        'slug',
        'price',
        'category',
        'brand',
        'vendor',
        'is_active',
        'is_featured',
        'is_visible',
        'is_featured_on_homepage',
        'is_new',
        'is_on_sale',
        'sort_order',
        'is_taxable',
        'image',
        'thumbnail',
        'subscription_price',
        'subscription_interval',
        'billing_cycle',
        'setup_fee',
        'cancellation_fee',
        'upgrade_fee',
        'downgrade_fee',
        'subscription_terms',
        'minimum_subscription_period',
        'trial_period_days',
        'free_trial',
        'auto_renew',
        'prorate_changes',
        'access_url',
        'access_credentials',
        'access_instructions',
        'included_features',
        'excluded_features',
        'feature_description',
        'allow_upgrade',
        'allow_downgrade',
        'allow_pause',
        'pause_limit_days',
        'allow_cancellation',
        'allow_multiple_subscriptions',
        'content_update_frequency',
        'content_description',
        'includes_updates',
        'includes_support',
        'update_schedule',
        'user_limit',
        'device_limit',
        'storage_limit_mb',
        'usage_restrictions',
        'concurrent_sessions',
        'geographic_restrictions',
        'billing_method',
        'grace_period_days',
        'billing_notes',
        'send_invoice',
        'auto_payment',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'additional_info',
        'custom_fields',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_visible' => 'boolean',
        'is_featured_on_homepage' => 'boolean',
        'is_new' => 'boolean',
        'is_on_sale' => 'boolean',
        'is_taxable' => 'boolean',
        'price' => 'decimal:2',
        'subscription_price' => 'decimal:2',
        'setup_fee' => 'decimal:2',
        'cancellation_fee' => 'decimal:2',
        'upgrade_fee' => 'decimal:2',
        'downgrade_fee' => 'decimal:2',
        'billing_cycle' => 'integer',
        'minimum_subscription_period' => 'integer',
        'trial_period_days' => 'integer',
        'free_trial' => 'boolean',
        'auto_renew' => 'boolean',
        'prorate_changes' => 'boolean',
        'allow_upgrade' => 'boolean',
        'allow_downgrade' => 'boolean',
        'allow_pause' => 'boolean',
        'pause_limit_days' => 'integer',
        'allow_cancellation' => 'boolean',
        'allow_multiple_subscriptions' => 'boolean',
        'includes_updates' => 'boolean',
        'includes_support' => 'boolean',
        'user_limit' => 'integer',
        'device_limit' => 'integer',
        'storage_limit_mb' => 'integer',
        'concurrent_sessions' => 'integer',
        'grace_period_days' => 'integer',
        'send_invoice' => 'boolean',
        'auto_payment' => 'boolean',
        'included_features' => 'array',
        'excluded_features' => 'array',
        'meta_keywords' => 'array',
        'custom_fields' => 'array',
    ];

    protected $hidden = [
        'access_credentials',
    ];

    // Accessors
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        return null;
    }

    public function getFormattedIntervalAttribute()
    {
        $interval = $this->subscription_interval;
        $cycle = $this->billing_cycle;

        if ($cycle > 1) {
            return $cycle . ' ' . Str::plural($interval, $cycle);
        }

        return $interval;
    }

    public function getEffectivePriceAttribute()
    {
        return $this->subscription_price ?: $this->price;
    }

    public function getTrialTextAttribute()
    {
        if (!$this->free_trial || $this->trial_period_days <= 0) {
            return 'No trial';
        }

        if ($this->trial_period_days === 1) {
            return '1 day free trial';
        } elseif ($this->trial_period_days < 7) {
            return $this->trial_period_days . ' days free trial';
        } elseif ($this->trial_period_days < 30) {
            $weeks = ceil($this->trial_period_days / 7);
            return $weeks . ' week' . ($weeks > 1 ? 's' : '') . ' free trial';
        } else {
            $months = ceil($this->trial_period_days / 30);
            return $months . ' month' . ($months > 1 ? 's' : '') . ' free trial';
        }
    }

    public function getIsUnlimitedUsersAttribute()
    {
        return $this->user_limit === null;
    }

    public function getIsUnlimitedDevicesAttribute()
    {
        return $this->device_limit === null;
    }

    public function getIsUnlimitedStorageAttribute()
    {
        return $this->storage_limit_mb === null;
    }

    public function getFormattedStorageLimitAttribute()
    {
        if ($this->is_unlimited_storage) {
            return 'Unlimited';
        }

        if ($this->storage_limit_mb < 1024) {
            return $this->storage_limit_mb . ' MB';
        } elseif ($this->storage_limit_mb < 1048576) {
            return round($this->storage_limit_mb / 1024, 1) . ' GB';
        } else {
            return round($this->storage_limit_mb / 1048576, 1) . ' TB';
        }
    }

    public function getRequiresSetupFeeAttribute()
    {
        return $this->setup_fee > 0;
    }

    // Mutators
    public function setSlugAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['slug'] = Str::slug($this->name);
        } else {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    public function setSkuAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['sku'] = 'SUB-' . strtoupper(Str::random(8));
        } else {
            $this->attributes['sku'] = $value;
        }
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOnSale($query)
    {
        return $query->where('is_on_sale', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByBrand($query, $brand)
    {
        return $query->where('brand', $brand);
    }

    public function scopeByInterval($query, $interval)
    {
        return $query->where('subscription_interval', $interval);
    }

    public function scopeByBillingMethod($query, $billingMethod)
    {
        return $query->where('billing_method', $billingMethod);
    }

    public function scopeWithFreeTrial($query)
    {
        return $query->where('free_trial', true);
    }

    public function scopeAutoRenew($query)
    {
        return $query->where('auto_renew', true);
    }

    public function scopeAllowUpgrade($query)
    {
        return $query->where('allow_upgrade', true);
    }

    public function scopeAllowDowngrade($query)
    {
        return $query->where('allow_downgrade', true);
    }

    public function scopeAllowPause($query)
    {
        return $query->where('allow_pause', true);
    }

    public function scopeByUpdateFrequency($query, $frequency)
    {
        return $query->where('content_update_frequency', $frequency);
    }

    public function scopeIncludesUpdates($query)
    {
        return $query->where('includes_updates', true);
    }

    public function scopeIncludesSupport($query)
    {
        return $query->where('includes_support', true);
    }

    // Relationships (if needed)
    // public function subscriptions()
    // {
    //     return $this->hasMany(Subscription::class);
    // }

    // public function orders()
    // {
    //     return $this->hasMany(Order::class);
    // }

    // Helper methods
    public function isAvailableForSubscription()
    {
        return $this->is_active && $this->is_visible;
    }

    public function canBeSubscribedTo()
    {
        return $this->isAvailableForSubscription();
    }

    public function getMonthlyPrice()
    {
        switch ($this->subscription_interval) {
            case 'daily':
                return $this->effective_price * 30;
            case 'weekly':
                return $this->effective_price * 4.33; // 52 weeks / 12 months
            case 'monthly':
                return $this->effective_price;
            case 'quarterly':
                return $this->effective_price / 3;
            case 'yearly':
                return $this->effective_price / 12;
            default:
                return $this->effective_price;
        }
    }

    public function getYearlyPrice()
    {
        switch ($this->subscription_interval) {
            case 'daily':
                return $this->effective_price * 365;
            case 'weekly':
                return $this->effective_price * 52;
            case 'monthly':
                return $this->effective_price * 12;
            case 'quarterly':
                return $this->effective_price * 4;
            case 'yearly':
                return $this->effective_price;
            default:
                return $this->effective_price;
        }
    }

    public function getTotalSetupCost()
    {
        return $this->effective_price + $this->setup_fee;
    }

    public function hasFeature($feature)
    {
        if (!$this->included_features) {
            return false;
        }

        return in_array($feature, $this->included_features);
    }

    public function excludesFeature($feature)
    {
        if (!$this->excluded_features) {
            return false;
        }

        return in_array($feature, $this->excluded_features);
    }

    public function isFeatureAvailable($feature)
    {
        return $this->hasFeature($feature) && !$this->excludesFeature($feature);
    }
}
