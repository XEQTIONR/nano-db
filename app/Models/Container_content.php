<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Container_content extends Model
{
    //

  public function tyre()
  {
    return $this->belongsTo(Tyre::class, 'tyre_id');
  }
}
