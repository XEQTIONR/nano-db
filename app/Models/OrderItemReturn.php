<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItemReturn extends Model
{
    //
    protected $table = 'returns';

    protected $fillable = [
        'order_num',
        'tyre_id',
        'bol',
        'container_num',
        'unit_price',
        'qty'
    ];

    public function order(): BelongsTo 
    {
        return $this->belongsTo(Order::class, 'order_num');
    }

    public function tyre()
    {
        return $this->belongsTo(Tyre::class, 'tyre_id', 'tyre_id');
    }
}