<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Performa_invoice extends Model
{
    protected $table = 'performa_invoices';
  //Performa invoice contains composite keys. Caution.
    //protected $casts = ['lc_num' => 'string'];


  public function tyre()
  {
    return $this->belongsTo(Tyre::class, 'tyre_id');
  }

}
