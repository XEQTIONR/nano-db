<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Consignment extends Model
{
    public $incrementing = false;
    
    protected $primaryKey = 'BOL';

    protected $fillable = [
        'BOL',
        'value',
        'exchange_rate',
        'tax',
        'land_date',
        'lc',
    ];
    
    protected function casts() : array  
    { 
        return [
            'land_date' => 'date',
        ];
    }

    public function letterOfCredit(): BelongsTo
    {
        return $this->belongsTo(LetterOfCredit::class, 'lc');
    }

    public function containers(): HasMany
    {
        return $this->hasMany(Container::class, 'BOL');
    }




}
