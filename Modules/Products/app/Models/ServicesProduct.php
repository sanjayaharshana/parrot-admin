<?php

namespace Modules\Products\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ServicesProduct extends Model
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
        'service_type',
        'duration_type',
        'duration_value',
        'service_scope',
        'deliverables',
        'pricing_model',
        'hourly_rate',
        'daily_rate',
        'monthly_rate',
        'setup_fee',
        'cancellation_fee',
        'prerequisites',
        'requirements',
        'whats_included',
        'whats_not_included',
        'terms_conditions',
        'requires_consultation',
        'requires_quote',
        'lead_time_days',
        'availability_schedule',
        'timezone_requirements',
        'service_provider',
        'provider_credentials',
        'provider_experience',
        'provider_certifications',
        'quality_guarantee',
        'satisfaction_guarantee',
        'warranty_days',
        'refund_policy',
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
        'hourly_rate' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'monthly_rate' => 'decimal:2',
        'setup_fee' => 'decimal:2',
        'cancellation_fee' => 'decimal:2',
        'duration_value' => 'integer',
        'lead_time_days' => 'integer',
        'warranty_days' => 'integer',
        'requires_consultation' => 'boolean',
        'requires_quote' => 'boolean',
        'meta_keywords' => 'array',
        'custom_fields' => 'array',
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

    public function getFormattedDurationAttribute()
    {
        if (!$this->duration_value) {
            return 'N/A';
        }

        $duration = $this->duration_value;
        $type = $this->duration_type;

        switch ($type) {
            case 'hourly':
                return $duration . ' hour' . ($duration > 1 ? 's' : '');
            case 'daily':
                return $duration . ' day' . ($duration > 1 ? 's' : '');
            case 'weekly':
                return $duration . ' week' . ($duration > 1 ? 's' : '');
            case 'monthly':
                return $duration . ' month' . ($duration > 1 ? 's' : '');
            case 'project_based':
                return 'Project-based';
            case 'one_time':
                return 'One-time';
            default:
                return $duration . ' ' . $type;
        }
    }

    public function getEffectivePriceAttribute()
    {
        switch ($this->pricing_model) {
            case 'hourly':
                return $this->hourly_rate;
            case 'daily':
                return $this->daily_rate;
            case 'monthly':
                return $this->monthly_rate;
            case 'project_based':
            case 'fixed':
            default:
                return $this->price;
        }
    }

    public function getRequiresConsultationAttribute()
    {
        return $this->requires_consultation || $this->requires_quote;
    }

    public function getIsCustomServiceAttribute()
    {
        return $this->service_type === 'custom';
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
            $this->attributes['sku'] = 'SRV-' . strtoupper(Str::random(8));
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

    public function scopeByServiceType($query, $serviceType)
    {
        return $query->where('service_type', $serviceType);
    }

    public function scopeByPricingModel($query, $pricingModel)
    {
        return $query->where('pricing_model', $pricingModel);
    }

    public function scopeByDurationType($query, $durationType)
    {
        return $query->where('duration_type', $durationType);
    }

    public function scopeRequiresConsultation($query)
    {
        return $query->where('requires_consultation', true);
    }

    public function scopeRequiresQuote($query)
    {
        return $query->where('requires_quote', true);
    }

    public function scopeCustomServices($query)
    {
        return $query->where('service_type', 'custom');
    }

    // Relationships (if needed)
    // public function orders()
    // {
    //     return $this->hasMany(Order::class);
    // }

    // public function appointments()
    // {
    //     return $this->hasMany(Appointment::class);
    // }

    // Helper methods
    public function isAvailableForBooking()
    {
        return $this->is_active && $this->is_visible;
    }

    public function canBeBookedImmediately()
    {
        return !$this->requires_consultation && !$this->requires_quote;
    }

    public function getEstimatedCost($duration = null)
    {
        if ($this->pricing_model === 'fixed') {
            return $this->price;
        }

        if ($duration && $this->pricing_model === 'hourly') {
            return $this->hourly_rate * $duration;
        }

        if ($duration && $this->pricing_model === 'daily') {
            return $this->daily_rate * $duration;
        }

        if ($duration && $this->pricing_model === 'monthly') {
            return $this->monthly_rate * $duration;
        }

        return $this->effective_price;
    }

    public function getTotalCost($duration = null)
    {
        $baseCost = $this->getEstimatedCost($duration);
        return $baseCost + $this->setup_fee;
    }
}
