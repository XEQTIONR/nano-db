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
}
