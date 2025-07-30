<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContainerContent extends Model
{
    public function casts() {
        return [
            'qty' => 'integer',
        ];
    }
}
