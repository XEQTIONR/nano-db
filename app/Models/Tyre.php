<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tyre extends Model
{   
    protected $primaryKey = 'tyre_id';

    protected $fillable = [
        'brand',
        'size',
        'pattern',
        'lisi',
    ];

    public static $searchable = [
        'brand',
        'size',
        'pattern',
        'lisi',
    ];

    // protected $appends = [
    //     'supplied_qty',
    //     'ordered_qty',
    //     'in_stock',
    // ];

    protected function casts(): array 
    {
        return [
            'supplied_qty' => 'double',
            'ordered_qty' => 'double',
            'wasted_qty' => 'double',
            'in_stock' => 'double',
        ];
    }
}
