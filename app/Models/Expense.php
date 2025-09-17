<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class Expense extends Model
{
    const EXPENSEABLE_DAILY = 'daily';
    const EXPENSEABLE_MONTHLY = 'monthly';
    const EXPENSEABLE_YEARLY = 'yearly';

    const EXPENSABLE_TYPES = [
        self::EXPENSEABLE_DAILY => 'Daily',
        self::EXPENSEABLE_MONTHLY => 'Monthly',
        self::EXPENSEABLE_YEARLY => 'Yearly',
        BankAccount::class => 'Bank Account',
        Container::class => 'Container',
        Consignment::class => 'Consignment',
        LetterOfCredit::class => 'Letter of Credit',
        Order::class => 'Order',
    ];


    protected $fillable = [
        'expensable_type',
        'expensable_id',
        'date',
        'amount',
        'note',
        'rate',
    ];
    
    protected $appends = [
        'amount_local',
    ];

    protected function casts() {
        return [
            'date' => 'date',
            'amount' => 'float',
            'rate' => 'float',
        ];
    }

    public function expensable(): MorphTo
    {
        return $this->morphTo();
    }

    public function amountLocal(): Attribute
    {
        return Attribute::make(
            get: function(mixed $val, array $attr) {
               return $attr['rate'] * $attr['amount'];
            }
        );
    }



    
}
