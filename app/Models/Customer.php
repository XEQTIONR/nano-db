<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //

    public function orders()
    {
      return $this->hasMany(Order::class, 'customer_id');
    }

    public function payments()
    {
      return $this->hasManyThrough(Payment::class, Order::class, 'customer_id', 'Order_num');
    }

}
