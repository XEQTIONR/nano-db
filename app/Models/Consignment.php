<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consignment extends Model
{
    //
    public $primaryKey = 'BOL';
    public $incrementing = false;
    //protected $casts = ['BOL' => 'string']; // laravel 5.0

    public function letterOfCredit()
    {
      return $this->belongsTo(Lc::class, 'lc');
    }

    public function containers()
    {
      return $this->hasMany(Consignment_container::class,'BOL');
    }

    public function expenses()
    {
      return $this->hasMany(Consignment_expense::class,'BOL');
    }

    public function waste()
    {
      return $this->hasMany(Waste::class, 'BOL');
    }
}
