<?php

namespace App\Models;

use App\Traits\Expensable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class LetterOfCredit extends Model
{
    use Expensable;
    
    protected $table = 'lcs';

    protected $primaryKey = 'lc_num';

    public $incrementing = false;

    protected $fillable = [
        'lc_num',
        'date_issued',
        'date_expiry',
        'applicant',
        'beneficiary',
        'currency_code',
        'foreign_amount',
        'foreign_expense',
        'domestic_expense',
        'exchange_rate',
        'port_depart',
        'port_arrive',
        'invoice_no',
        'notes',
    ];

    protected function casts() {
        return [
            'lc_num' => 'string',
            'date_issued' => 'date',
            'date_expiry' => 'date',
            'foreign_amount' => 'float',
            'foreign_expense' => 'float',
            'domestic_expense' => 'float',
            'exchange_rate' => 'float',
        ];
    }

    public function consignments()
    {
        return $this->hasMany(Consignment::class, 'lc');   
    }

    public function items()
    {
        return $this->hasMany(ProformaInvoiceItem::class, 'lc_num', 'lc_num');
    }

    protected function localAmount(): Attribute
    {
        return Attribute::make(
            get: function(mixed $val, array $attr) {
                return $attr['exchange_rate'] * $attr['foreign_amount'];
            }
        );
    }
}
