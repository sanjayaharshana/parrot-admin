<?php

namespace Modules\Products\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class DigitalProduct extends Model
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
        'download_link',
        'file_path',
        'file_name',
        'file_extension',
        'file_size',
        'file_size_formatted',
        'download_limit',
        'download_expiry_days',
        'requires_login',
        'instant_download',
        'license_type',
        'license_terms',
        'usage_rights',
        'allow_resale',
        'allow_modification',
        'access_url',
        'access_credentials',
        'access_instructions',
        'delivery_method',
        'compatible_platforms',
        'compatible_software',
        'minimum_requirements',
        'recommended_requirements',
        'version',
        'release_date',
        'auto_updates',
        'update_notes',
        'preview_url',
        'demo_url',
        'has_preview',
        'has_demo',
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
        'file_size' => 'integer',
        'download_limit' => 'integer',
        'download_expiry_days' => 'integer',
        'requires_login' => 'boolean',
        'instant_download' => 'boolean',
        'allow_resale' => 'boolean',
        'allow_modification' => 'boolean',
        'auto_updates' => 'boolean',
        'has_preview' => 'boolean',
        'has_demo' => 'boolean',
        'release_date' => 'date',
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

    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) {
            return 'N/A';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    public function getIsDownloadableAttribute()
    {
        return !empty($this->download_link) || !empty($this->file_path);
    }

    public function getRequiresAuthenticationAttribute()
    {
        return $this->requires_login;
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
            $this->attributes['sku'] = 'DIG-' . strtoupper(Str::random(8));
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

    public function scopeByLicenseType($query, $licenseType)
    {
        return $query->where('license_type', $licenseType);
    }

    public function scopeByDeliveryMethod($query, $deliveryMethod)
    {
        return $query->where('delivery_method', $deliveryMethod);
    }

    public function scopeRequiresLogin($query)
    {
        return $query->where('requires_login', true);
    }

    public function scopeInstantDownload($query)
    {
        return $query->where('instant_download', true);
    }

    public function scopeHasPreview($query)
    {
        return $query->where('has_preview', true);
    }

    public function scopeHasDemo($query)
    {
        return $query->where('has_demo', true);
    }

    // Relationships (if needed)
    // public function orders()
    // {
    //     return $this->hasMany(Order::class);
    // }

    // public function downloads()
    // {
    //     return $this->hasMany(Download::class);
    // }

    // Helper methods
    public function isAvailableForDownload()
    {
        return $this->is_active && $this->is_downloadable;
    }

    public function canBeDownloadedBy($user = null)
    {
        if (!$this->isAvailableForDownload()) {
            return false;
        }

        if ($this->requires_login && !$user) {
            return false;
        }

        return true;
    }

    public function getDownloadUrl($user = null)
    {
        if (!$this->canBeDownloadedBy($user)) {
            return null;
        }

        return $this->download_link ?: $this->file_path;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });

        static::updating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }
}
