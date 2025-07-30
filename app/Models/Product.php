<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'price',
        'stock_quantity',
        'sku',
        'status',
        'image',
        'category'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
    ];

    /**
     * Get the category name
     */
    public function getCategoryAttribute($value)
    {
        $categories = [
            1 => 'Electronics',
            2 => 'Clothing',
            3 => 'Books',
            4 => 'Home & Garden'
        ];
        
        return $categories[$value] ?? 'Unknown';
    }

    /**
     * Scope for active products
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for products in stock
     */
    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    /**
     * Get the product image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        return 'https://via.placeholder.com/300x300?text=No+Image';
    }
} 