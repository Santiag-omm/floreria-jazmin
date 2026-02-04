<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    public const TARGET_GENERAL = 'general';
    public const TARGET_PREMIUM = 'premium';

    protected $fillable = [
        'title',
        'description',
        'discount_percent',
        'target_type',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        $today = now()->toDateString();

        return $query
            ->where('is_active', true)
            ->where(function ($inner) use ($today) {
                $inner->whereNull('starts_at')->orWhereDate('starts_at', '<=', $today);
            })
            ->where(function ($inner) use ($today) {
                $inner->whereNull('ends_at')->orWhereDate('ends_at', '>=', $today);
            });
    }

    public static function discountForProduct(Product $product, $promotions = null): float
    {
        $promos = $promotions ?? self::active()->get();

        $eligible = $promos->filter(function (self $promotion) use ($product) {
            $target = $promotion->target_type ?: self::TARGET_GENERAL;

            if ($target === self::TARGET_GENERAL) {
                return true;
            }

            return $target === self::TARGET_PREMIUM && $product->is_premium;
        });

        return (float) ($eligible->max('discount_percent') ?? 0);
    }

    public static function applyDiscount(float $price, float $discountPercent): float
    {
        if ($discountPercent <= 0) {
            return $price;
        }

        return round($price * (1 - ($discountPercent / 100)), 2);
    }
}
