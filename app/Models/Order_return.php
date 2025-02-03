<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Order_return extends Model
{
    //

  protected $table = 'returns';

  public $incrementing = false;

  public function order()
  {
    return $this->belongsTo(Order::class, 'order_num');
  }

  public function tyre()
  {
    return $this->belongsTo(Tyre::class, 'tyre_id');
  }
}
