<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Waste extends Model
{
    protected $table ='waste';
    
    protected $fillable = [
        'Container_num',
        'BOL',
        'tyre_id',
        'qty'
    ];

    public function tyre(): BelongsTo
    {
        return $this->belongsTo(Tyre::class, 'tyre_id', 'tyre_id');
    }
}
