<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_num' => $this->Order_num,
            'order_on' => $this->order_on->toDateString(),
            'customer_id' => $this->customer_id,
            'customer_name' => $this->name,
            'discount_percent' => $this->discount_percent,
            'discount_amount' => $this->discount_amount,
            'tax_percentage' => $this->tax_percentage,
            'tax_amount' => $this->tax_amount,
            'grand_total' => floatval($this->grand_total),
            'payments_total' => floatval($this->payments_total),
            'commission' => floatval($this->commission),
            'balance' => floatval($this->balance),
            'count' => $this->num_items,
            'count_payments' => $this->count_payments,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
