<?php

namespace Modules\Subscriptions\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'subscription_plans';

    protected $fillable = [
        'name',
        'description',
        'slug',
        'is_active',
        'stripe_product_id',
    ];

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(\Modules\Subscriptions\Models\Feature::class, 'plan_feature', 'subscription_plan_id', 'subscription_feature_id')
            ->withPivot(['limit', 'enabled'])
            ->withTimestamps();
    }

    public function prices(): HasMany
    {
        return $this->hasMany(\Modules\Subscriptions\Models\PlanPrice::class, 'subscription_plan_id');
    }
}


