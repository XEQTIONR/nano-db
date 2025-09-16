<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Expense;

trait Expensable {

    public function expenses(): MorphMany
    {
        return $this->morphMany(Expense::class, 'expenseable', 'string');
    }
}