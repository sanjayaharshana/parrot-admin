<?php

namespace Modules\Products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        // Essential Information
        'name',
        'product_type',
        'category',
        'sku',
        'is_active',
        
        // Product Details
        'description',
        'brand',
        'slug',
        'vendor',
        'warranty',
        
        // Media & Images
        'image',
        'thumbnail',
        
        // Pricing & Billing
        'price',
        'subscription_price',
        'shipping_cost',
        'tax_rate',
        
        // Inventory & Shipping
        'stock_quantity',
        'stock_status',
        'weight',
        'dimensions',
        'barcode',
        
        // Product Settings
        'is_featured',
        'is_visible',
        'is_featured_on_homepage',
        'is_new',
        'is_on_sale',
        'is_taxable',
        'requires_shipping',
        'is_downloadable',
        'sort_order',
        
        // Digital & Subscription
        'download_link',
        'file_size',
        'access_url',
        'access_credentials',
        'subscription_interval',
        'subscription_duration',
        'subscription_terms',
        'auto_renew',
        'license_key',
        'license_type',
        
        // SEO & Marketing
        'meta_title',
        'meta_description',
        'meta_keywords',
        
        // Additional Information
        'additional_info',
        'custom_fields',
        'shipping_class',
        'delivery_method',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'price' => 'decimal:2',
        'subscription_price' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'weight' => 'decimal:2',
        'stock_quantity' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_visible' => 'boolean',
        'is_featured_on_homepage' => 'boolean',
        'is_new' => 'boolean',
        'is_on_sale' => 'boolean',
        'is_taxable' => 'boolean',
        'requires_shipping' => 'boolean',
        'is_downloadable' => 'boolean',
        'auto_renew' => 'boolean',
        'custom_fields' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'access_credentials',
        'license_key',
    ];

    /**
     * Get the category relationship
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the vendor relationship
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    /**
     * Get the brand relationship
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /**
     * Scope for active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured products
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for visible products
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    /**
     * Scope for in stock products
     */
    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    /**
     * Scope for products by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('product_type', $type);
    }

    /**
     * Scope for products by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get the product image URL
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

    /**
     * Get the product thumbnail URL
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        return $this->image_url;
    }

    /**
     * Check if product is on sale
     */
    public function getIsOnSaleAttribute(): bool
    {
        return $this->attributes['is_on_sale'] && $this->subscription_price > 0;
    }

    /**
     * Get the sale price
     */
    public function getSalePriceAttribute(): ?float
    {
        if ($this->is_on_sale && $this->subscription_price > 0) {
            return $this->subscription_price;
        }
        return null;
    }

    /**
     * Get the final price (sale price if available, otherwise regular price)
     */
    public function getFinalPriceAttribute(): float
    {
        return $this->sale_price ?? $this->price;
    }

    /**
     * Check if product is out of stock
     */
    public function getIsOutOfStockAttribute(): bool
    {
        return $this->stock_quantity <= 0;
    }

    /**
     * Check if product is low on stock
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->stock_quantity > 0 && $this->stock_quantity < 10;
    }

    /**
     * Get stock status text
     */
    public function getStockStatusTextAttribute(): string
    {
        if ($this->is_out_of_stock) {
            return 'Out of Stock';
        }
        if ($this->is_low_stock) {
            return 'Low Stock';
        }
        return 'In Stock';
    }

    /**
     * Get product type label
     */
    public function getProductTypeLabelAttribute(): string
    {
        return match($this->product_type) {
            'physical' => 'Physical Product',
            'digital' => 'Digital Product',
            'subscription' => 'Subscription Product',
            default => 'Unknown'
        };
    }

    /**
     * Get subscription interval label
     */
    public function getSubscriptionIntervalLabelAttribute(): ?string
    {
        if ($this->product_type !== 'subscription') {
            return null;
        }

        return match($this->subscription_interval) {
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
            'yearly' => 'Yearly',
            'weekly' => 'Weekly',
            'daily' => 'Daily',
            default => 'Unknown'
        };
    }

    /**
     * Boot method to set default values
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->sku)) {
                $product->sku = 'SKU-' . strtoupper(uniqid());
            }
            
            if (empty($product->slug)) {
                $product->slug = \Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = \Str::slug($product->name);
            }
        });
    }
}
