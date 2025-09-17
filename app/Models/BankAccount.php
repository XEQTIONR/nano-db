<?php

namespace App\Models;

use App\Traits\Expensable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankAccount extends Model
{
    use Expensable;

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'account');
    }
}
