<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'warehouse_id', 'order_number', 'status',
        'subtotal', 'shipping_cost', 'point_redeemed', 'point_earned', 'total',
        'buyer_name', 'buyer_email', 'buyer_phone',
        'shipping_address', 'shipping_note', 'note', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'integer',
            'subtotal' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'point_redeemed' => 'integer',
            'point_earned' => 'integer',
            'total' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function shipment(): HasOne
    {
        return $this->hasOne(Shipment::class);
    }

    public function pointTransactions(): HasMany
    {
        return $this->hasMany(PointTransaction::class);
    }
}
