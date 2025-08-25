<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContainerContent extends Model
{
    public function casts() {
        return [
            'qty' => 'integer',
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
}
