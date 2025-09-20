<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class Expense extends Model
{
    const EXPENSABLE_DAILY = 'daily';
    const EXPENSABLE_MONTHLY = 'monthly';
    const EXPENSABLE_YEARLY = 'yearly';

    const EXPENSE_TYPES = [
        self::EXPENSABLE_DAILY, 
        self::EXPENSABLE_MONTHLY,
        self::EXPENSABLE_YEARLY ,
        BankAccount::class,
        Container::class,
        Consignment::class,
        LetterOfCredit::class,
        Order::class 
    ];
    
    const EXPENSABLE_LABELS = [
        self::EXPENSABLE_DAILY => 'Daily',
        self::EXPENSABLE_MONTHLY => 'Monthly',
        self::EXPENSABLE_YEARLY => 'Yearly',
        BankAccount::class => 'Bank Account',
        Container::class => 'Container',
        Consignment::class => 'Consignment',
        LetterOfCredit::class => 'Letter of Credit',
        Order::class => 'Order',
    ];

    public static $searchable = [
        'id',
        'expensable_type',
        'expensable_id',
        'date',
        'amount',
        'note',
        'rate',
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
