<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProformaInvoiceItem extends Model
{
    //
    protected $table = 'performa_invoices';

    public $incrementing = false;

    protected $guarded = [

    ];

    protected $casts = [
        'lc_num' => 'string'
    ];


}
