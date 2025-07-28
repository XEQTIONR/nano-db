<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class LetterOfCreditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'lc_num' => $this->lc_num,
            'date_issued' => $this->date_issued->toDateString(),
            'date_expiry' => $this->date_expiry->toDateString(),
            'applicant' => $this->applicant,
            'beneficiary' => $this->beneficiary,
            'currency_code' => $this->currency_code,
            'exchange_rate' => $this->exchange_rate,
            'foreign_amount' => $this->foreign_amount,
            'local_amount' => $this->local_amount,
            'foreign_expense' => $this->foreign_expense,
            'domestic_expense' => $this->domestic_expense,
            'total_expense' => ($this->foreign_expense * $this->exchange_rate + $this->domestic_expense),
            'created_at' => $this->created_at->toDateTimeString(),    
        ];
    }
}
