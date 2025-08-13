<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'transaction_id' => $this->transaction_id,
            'order_num' => $this->Order_num,
            'payment_amount' => $this->payment_amount,
            'refund_amount' => $this->refund_amount,
            'amount' => $this->amount,
            'type' => $this->type,
            'account' => $this->account,
            $this->mergeWhen($this->relationLoaded('bankAccount') && $this->account > 0, [
                'accountDesc' => $this->bankAccount?->bank_name . " - " . $this->bankAccount?->account_number
            ]),
            'order' => OrderResource::make($this->whenLoaded('order')),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
