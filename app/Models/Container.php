<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Container extends Model
{
    //
    protected $table = 'consignment_containers';

    protected $primaryKey = 'Container_num';

    public $incrementing = false;

    protected $fillable = [
        'BOL',
        'Container_num'
    ];

    public function consignment(): BelongsTo
    {
        return $this->belongsTo(Consignment::class, 'BOL');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(ContainerContent::class, 'Container_num');
    }
}
