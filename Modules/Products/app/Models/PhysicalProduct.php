<?php

namespace Modules\Products\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PhysicalProduct extends Model
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
        'stock_quantity',
        'stock_status',
        'low_stock_threshold',
        'track_inventory',
        'allow_backorders',
        'max_backorder_quantity',
        'weight',
        'weight_unit',
        'dimensions',
        'dimension_unit',
        'color',
        'size',
        'material',
        'model_number',
        'part_number',
        'shipping_cost',
        'requires_shipping',
        'shipping_class',
        'delivery_method',
        'handling_time_days',
        'free_shipping',
        'free_shipping_threshold',
        'barcode',
        'upc',
        'ean',
        'isbn',
        'gtin',
        'warranty',
        'return_days',
        'return_policy',
        'refundable',
        'exchangeable',
        'requires_assembly',
        'assembly_instructions',
        'requires_installation',
        'installation_instructions',
        'includes_tools',
        'included_items',
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
        'stock_quantity' => 'integer',
        'low_stock_threshold' => 'integer',
        'track_inventory' => 'boolean',
        'allow_backorders' => 'boolean',
        'max_backorder_quantity' => 'integer',
        'weight' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'requires_shipping' => 'boolean',
        'handling_time_days' => 'integer',
        'free_shipping' => 'boolean',
        'free_shipping_threshold' => 'decimal:2',
        'return_days' => 'integer',
        'refundable' => 'boolean',
        'exchangeable' => 'boolean',
        'requires_assembly' => 'boolean',
        'requires_installation' => 'boolean',
        'includes_tools' => 'boolean',
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

    public function getFormattedWeightAttribute()
    {
        if (!$this->weight) {
            return 'N/A';
        }

        return $this->weight . ' ' . $this->weight_unit;
    }

    public function getFormattedDimensionsAttribute()
    {
        if (!$this->dimensions) {
            return 'N/A';
        }

        return $this->dimensions . ' ' . $this->dimension_unit;
    }

    public function getIsOutOfStockAttribute()
    {
        return $this->stock_status === 'out_of_stock';
    }

    public function getIsLowStockAttribute()
    {
        return $this->stock_status === 'low_stock';
    }

    public function getIsInStockAttribute()
    {
        return $this->stock_status === 'in_stock';
    }

    public function getCanBackorderAttribute()
    {
        return $this->allow_backorders && $this->max_backorder_quantity > 0;
    }

    public function getEffectiveShippingCostAttribute()
    {
        if ($this->free_shipping) {
            return 0.00;
        }

        if ($this->free_shipping_threshold && $this->price >= $this->free_shipping_threshold) {
            return 0.00;
        }

        return $this->shipping_cost;
    }

    public function getHandlingTimeTextAttribute()
    {
        if ($this->handling_time_days <= 1) {
            return 'Same day';
        } elseif ($this->handling_time_days <= 2) {
            return '1-2 business days';
        } elseif ($this->handling_time_days <= 5) {
            return '3-5 business days';
        } else {
            return $this->handling_time_days . ' business days';
        }
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
            $this->attributes['sku'] = 'PHY-' . strtoupper(Str::random(8));
        } else {
            $this->attributes['sku'] = $value;
        }
    }

    public function setStockStatusAttribute($value)
    {
        if ($this->track_inventory) {
            if ($this->stock_quantity <= 0) {
                $this->attributes['stock_status'] = 'out_of_stock';
            } elseif ($this->stock_quantity <= $this->low_stock_threshold) {
                $this->attributes['stock_status'] = 'low_stock';
            } else {
                $this->attributes['stock_status'] = 'in_stock';
            }
        } else {
            $this->attributes['stock_status'] = $value;
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

    public function scopeInStock($query)
    {
        return $query->where('stock_status', 'in_stock');
    }

    public function scopeLowStock($query)
    {
        return $query->where('stock_status', 'low_stock');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock_status', 'out_of_stock');
    }

    public function scopeRequiresShipping($query)
    {
        return $query->where('requires_shipping', true);
    }

    public function scopeFreeShipping($query)
    {
        return $query->where('free_shipping', true);
    }

    public function scopeByWeight($query, $minWeight, $maxWeight = null)
    {
        $query->where('weight', '>=', $minWeight);
        if ($maxWeight) {
            $query->where('weight', '<=', $maxWeight);
        }
        return $query;
    }

    public function scopeByColor($query, $color)
    {
        return $query->where('color', $color);
    }

    public function scopeBySize($query, $size)
    {
        return $query->where('size', $size);
    }

    public function scopeByMaterial($query, $material)
    {
        return $query->where('material', $material);
    }

    // Relationships (if needed)
    // public function orders()
    // {
    //     return $this->hasMany(Order::class);
    // }

    // public function inventory()
    // {
    //     return $this->hasMany(Inventory::class);
    // }

    // Helper methods
    public function isAvailableForPurchase()
    {
        return $this->is_active && $this->is_visible &&
               ($this->is_in_stock || $this->can_backorder);
    }

    public function canPurchaseQuantity($quantity)
    {
        if (!$this->isAvailableForPurchase()) {
            return false;
        }

        if ($this->track_inventory) {
            if ($this->is_in_stock) {
                return $quantity <= $this->stock_quantity;
            } elseif ($this->can_backorder) {
                return $quantity <= $this->max_backorder_quantity;
            }
            return false;
        }

        return true;
    }

    public function updateStock($quantity, $operation = 'decrease')
    {
        if (!$this->track_inventory) {
            return;
        }

        if ($operation === 'decrease') {
            $this->stock_quantity = max(0, $this->stock_quantity - $quantity);
        } elseif ($operation === 'increase') {
            $this->stock_quantity += $quantity;
        }

        $this->save();
    }

    public function getShippingCost($orderTotal = null)
    {
        if ($this->free_shipping) {
            return 0.00;
        }

        if ($orderTotal && $this->free_shipping_threshold && $orderTotal >= $this->free_shipping_threshold) {
            return 0.00;
        }

        return $this->shipping_cost;
    }
}
