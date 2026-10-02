<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'details',
        'price',
        'compare_at_price',
        'discount_percent',
        'stock_quantity',
        'low_stock_threshold',
        'sizes',
        'colors',
        'is_featured',
        'is_active',
        'views_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'discount_percent' => 'integer',
        'stock_quantity' => 'integer',
        'low_stock_threshold' => 'integer',
        'sizes' => 'array',
        'colors' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'views_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name, '-', null) . '-' . Str::random(5);
            }
            if (empty($product->sku)) {
                $product->sku = 'DJ-' . strtoupper(Str::random(6));
            }
            // Auto calculate discount percent if compare_at_price is set and higher than price
            if ($product->compare_at_price && $product->compare_at_price > $product->price && empty($product->discount_percent)) {
                $product->discount_percent = round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100);
            }
        });

        static::updating(function ($product) {
            if ($product->compare_at_price && $product->compare_at_price > $product->price) {
                $product->discount_percent = round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100);
            } else {
                $product->discount_percent = null;
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order', 'asc');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(ProductReview::class)->where('status', 'approved')->latest();
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Accessors & Helpers
    public function getMainImageUrlAttribute(): string
    {
        $primary = $this->images->firstWhere('is_primary', true) ?? $this->images->first();
        if ($primary) {
            return $primary->url;
        }
        return asset('images/logo.webp');
    }

    public function getHasDiscountAttribute(): bool
    {
        return !empty($this->compare_at_price) && $this->compare_at_price > $this->price;
    }

    public function getStockStatusAttribute(): array
    {
        if ($this->stock_quantity <= 0) {
            return [
                'status' => 'out_of_stock',
                'label' => 'نفذت الكمية',
                'class' => 'bg-red-50 text-red-700 border-red-200',
                'available' => false,
            ];
        }

        if ($this->stock_quantity <= ($this->low_stock_threshold ?: 3)) {
            return [
                'status' => 'low_stock',
                'label' => 'متبقي ' . $this->stock_quantity . ' قطع فقط',
                'class' => 'bg-amber-50 text-amber-800 border-amber-200',
                'available' => true,
            ];
        }

        return [
            'status' => 'in_stock',
            'label' => 'متوفر في المخزون',
            'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'available' => true,
        ];
    }

    // Rating calculations
    public function getAverageRatingAttribute(): float
    {
        $avg = $this->approvedReviews()->avg('rating');
        return $avg ? round($avg, 1) : 0.0;
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->approvedReviews()->count();
    }

    public function getRatingDistributionAttribute(): array
    {
        $total = $this->reviews_count;
        $distribution = [
            5 => 0,
            4 => 0,
            3 => 0,
            2 => 0,
            1 => 0,
        ];

        if ($total > 0) {
            $counts = $this->approvedReviews()
                ->selectRaw('rating, count(*) as count')
                ->groupBy('rating')
                ->pluck('count', 'rating')
                ->toArray();

            foreach ($distribution as $star => $val) {
                $count = $counts[$star] ?? 0;
                $percentage = round(($count / $total) * 100);
                $distribution[$star] = [
                    'count' => $count,
                    'percentage' => $percentage,
                ];
            }
        } else {
            foreach ($distribution as $star => $val) {
                $distribution[$star] = [
                    'count' => 0,
                    'percentage' => 0,
                ];
            }
        }

        return $distribution;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->where('stock_quantity', '>', 0)
                     ->whereColumn('stock_quantity', '<=', 'low_stock_threshold');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock_quantity', '<=', 0);
    }

    public function scopeSearch($query, $term)
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('sku', 'like', "%{$term}%")
              ->orWhere('short_description', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%")
              ->orWhereHas('category', function ($catQ) use ($term) {
                  $catQ->where('name', 'like', "%{$term}%");
              });
        });
    }
}
