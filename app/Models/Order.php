<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Order extends Model
{
    protected $primaryKey = "Order_num";

    protected $fillable = [
        'discount_percent',
        'discount_amount',
        'tax_percentage',
        'tax_amount',
        'commission',
        'customer_id',
        'order_on',
        'random'
    ];

    protected function casts(): array
    {
        return [
            'discount_percent' => 'float',
            'discount_amount' => 'float',
            'tax_percentage' => 'float',
            'tax_amount' => 'float',
            'commission' => 'float',
            'order_on' => 'date',
        ];
    } 

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function contents(): HasMany
    {
        return $this->hasMany(OrderContent::class, 'Order_num');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'Order_num');
    }

    public function returns(): HasMany
    {
        return $this->hasMany(OrderItemReturn::class, 'order_num');
    }

    public function expenses(): MorphMany
    {
        return $this->morphMany(Expense::class, 'expenseable', 'string');
    }
}
