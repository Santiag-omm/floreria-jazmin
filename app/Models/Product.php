<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'is_active',
        'is_premium',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_premium' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function coverImage(): ?ProductImage
    {
        if ($this->relationLoaded('images')) {
            return $this->images->firstWhere('is_cover', true)
                ?? $this->images->first();
        }

        return $this->images()->where('is_cover', true)->first();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
