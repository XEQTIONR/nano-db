<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BankAccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bank_name' => $this->bank_name,
            'account_name' => $this->account_name,
            'account_number' => $this->account_number,
            'bank_address' => $this->bank_address,
            'created_at' => $this->created_at
                ? $this->created_at->toDateTimeString()
                : null,
            'toString' => $this->bank_name . " Acct #" . $this->account_number
        ];
    }
}
