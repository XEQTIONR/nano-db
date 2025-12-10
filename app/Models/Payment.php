<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $primaryKey = 'transaction_id';

    protected $fillable = [
        'payment_amount',
        'refund_amount',
        'type',
        'account',
    ];

    public static $searchable = [
        'transaction_id',
        'Order_num',
        'payment_amount',
        'refund_amount',
        'type',
        'account',
    ];

    protected $appends = [
        'amount',
    ];

    public function casts() {
        return [
            'payment_amount' => 'float',
            'refund_amount' => 'float',
            'amount' => 'float'
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'Order_num');
    }

    public function bankAccount(): BelongsTo {
        return $this->belongsTo(BankAccount::class, 'account');
    }

    public function amount(): Attribute
    {
        return Attribute::make( get: function(mixed $val, array $attr) {
               return $attr['payment_amount'] - $attr['refund_amount'];
            }
        );
    }
}
