<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Expense extends Model
{
    protected $fillable = [
        'date',
        'amount',
        'note',
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
