<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContainerContent extends Model
{
    public function casts() {
        return [
            'Container_num' => 'string',
            'BOL' => 'string',
            'tyre_id' => 'integer',
            'qty' => 'integer',
            'unit_price' => 'float',
            'total_tax' => 'float',
            'total_weight' => 'float',
            'total' => 'float',
        ];
    }

    protected $fillable = [
        'Container_num',
        'BOL',
        'tyre_id',
        'qty',
        'unit_price',
        'total_tax',
        'total_weight',
    ];

    public $appends = [
        'total'
    ];

    public function tyre(): BelongsTo
    {
        return $this->belongsTo(Tyre::class, 'tyre_id', 'tyre_id');
    }

    public function total(): Attribute
    {
        return Attribute::make(
            get: function(mixed $val, array $attr) {
                return $attr['qty'] * $attr['unit_price'];
            }
        );
    }
}
