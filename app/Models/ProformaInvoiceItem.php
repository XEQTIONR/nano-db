<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProformaInvoiceItem extends Model
{
    //
    protected $table = 'performa_invoices';

    public $incrementing = false;

    protected $guarded = [

    ];

    protected function casts(): array 
    {
        return [
            'lc_num' => 'string'
        ];
    }

    public function tyre(): BelongsTo 
    {
        return $this->belongsTo(Tyre::class, 'tyre_id', 'tyre_id');
    }


}
