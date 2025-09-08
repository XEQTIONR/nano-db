<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waste extends Model
{
    protected $table ='waste';
    
    protected $fillable = [
        'Container_num',
        'BOL',
        'tyre_id',
        'qty'
    ];
}
