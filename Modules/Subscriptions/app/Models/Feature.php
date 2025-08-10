<?php

namespace Modules\Subscriptions\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $table = 'subscription_features';

    protected $fillable = [
        'name', 'key', 'unit', 'is_metered',
    ];
}


