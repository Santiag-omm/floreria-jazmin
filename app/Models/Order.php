<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const STATUS_PENDING = 'Pendiente';
    public const STATUS_PREPARATION = 'Preparación';
    public const STATUS_ON_ROUTE = 'En ruta';
    public const STATUS_DELIVERED = 'Entregado';

    protected $fillable = [
        'user_id',
        'code',
        'subtotal',
        'discount_total',
        'total',
        'status',
        'customer_name',
        'customer_phone',
        'customer_address',
        'notes',
        'placed_at',
    ];

    protected $casts = [
        'placed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
