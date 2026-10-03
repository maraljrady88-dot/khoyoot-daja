<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_path',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrlAttribute(): string
    {
        $path = $this->image_path;

        if (empty($path)) {
            return asset('images/logo.webp');
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $cleanPath = ltrim($path, '/');

        // If explicitly in images/ directory
        if (str_starts_with($cleanPath, 'images/')) {
            return asset($cleanPath);
        }

        // If path already starts with storage/
        if (str_starts_with($cleanPath, 'storage/')) {
            return asset($cleanPath);
        }

        // Standard storage upload (e.g. products/xyz.jpg)
        return asset('storage/' . $cleanPath);
    }
}
