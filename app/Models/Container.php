<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Container extends Model
{
    //
    protected $table = 'consignment_containers';

    protected $primaryKey = 'Container_num';

    public $incrementing = false;

    protected $fillable = [
        'BOL'
    ];

    public function consignment(): BelongsTo
    {
        return $this->belongsTo(Consignment::class, 'BOL');
    }
}
