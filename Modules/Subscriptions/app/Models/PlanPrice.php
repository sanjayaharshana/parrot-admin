<?php

namespace Modules\Subscriptions\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanPrice extends Model
{
    use HasFactory;

    protected $table = 'subscription_plan_prices';

    protected $fillable = [
        'subscription_plan_id', 'interval', 'currency', 'amount', 'stripe_price_id', 'active',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(\Modules\Subscriptions\Models\Plan::class, 'subscription_plan_id');
    }
}


