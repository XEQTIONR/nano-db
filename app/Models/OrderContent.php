<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderContent extends Model
{
    //
    protected $fillable = [
        'qty',
        'unit_price',
    ];

    protected $casts = [
        'qty' => 'integer',
        'unit_price' => 'float',
    ];

    protected $appends = [
        'item_total'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'Order_num');
    }

    public function tyre(): BelongsTo
    {
        return $this->belongsTo(Tyre::class, 'tyre_id', 'tyre_id');
    }

    public function itemTotal(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attr) => $attr['qty'] * $attr['unit_price']
        );
    }
}
