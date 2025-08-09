<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ship extends Model
{
    protected $fillable = [
        'name',
        'address',
        'ship'
    ];

    /**
     * Get the display name for the ship
     */
    public function getDisplayNameAttribute()
    {
        return $this->name . ' (' . $this->ship . ')';
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'ship_products')
            ->withPivot(['quantity', 'price'])
            ->withTimestamps();
    }
}
