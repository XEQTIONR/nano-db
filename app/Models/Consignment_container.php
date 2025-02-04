<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consignment_container extends Model
{
    //in DB primary key is [Container_num, BOL]
    public $primaryKey = 'Container_num';
    //protected $casts = ['Container_num' => 'string'];
    public $incrementing = false;
    
    public function contents()
    {
      return $this->hasMany(Container_content::class,'Container_num');
    }
}
