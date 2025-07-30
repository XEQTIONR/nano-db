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
            'order' => OrderResource::make($this->whenLoaded('order'))
        ];
    }
}
