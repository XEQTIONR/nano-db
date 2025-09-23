<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

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
            'invoice_no' => $this->invoice_no,
            'date_issued' => ($this->date_issued instanceof Carbon)
                ? $this->date_issued->toDateString()
                :(new Carbon($this->date_issued))->toDateString(),
            'date_expiry' => ($this->date_expiry instanceof Carbon)
                ? $this->date_expiry->toDateString()
                :(new Carbon($this->date_expiry))->toDateString(),
            'applicant' => $this->applicant,
            'beneficiary' => $this->beneficiary,
            'currency_code' => $this->currency_code,
            'exchange_rate' => $this->exchange_rate,
            'port_depart' => $this->port_depart,
            'port_arrive' => $this->port_arrive,
            'foreign_amount' => $this->foreign_amount,
            'local_amount' => $this->local_amount ?? ($this->foreign_amount * $this->exchange_rate),
            'foreign_expense' => $this->foreign_expense,
            'domestic_expense' => $this->domestic_expense,
            'total_expense' => ($this->foreign_expense * $this->exchange_rate + $this->domestic_expense),
            'created_at' => ($this->created_at instanceof Carbon)
                ? $this->created_at->toDateTimeString()
                : (new Carbon($this->created_at))->toDateTimeString(),
            'items' => $this->when( 
                ($request->route()->getName() === 'lcs.show'),
                fn() => ProformaInvoiceItemResource::collection($this->items)
            ),
            'consignments' => $this->when( 
                ($request->route()->getName() === 'lcs.show'),
                fn() => ConsignmentResource::collection($this->whenLoaded('consignments'))
            ),

            'expenses' => $this->when( 
                ($request->route()->getName() === 'lcs.show'),
                fn() => ExpenseResource::collection($this->whenLoaded('expenses'))
            ),
            'toString' => $this->lc_num
        ];
    }
}
