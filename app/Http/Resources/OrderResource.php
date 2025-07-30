<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $subTotalFn = fn(float $carry, $item) => ($carry + ($item->qty * $item->unit_price));
        $countFn = fn(int $carry, $item) => $carry + $item->qty;

        $discountFraction = $this->discount_percent/100.0;
        $taxFraction = $this->tax_percentage/100.0;
        $delta = $taxFraction - $discountFraction;

        return [
            'order_num' => $this->Order_num,
            'order_on' => $this->order_on->toDateString(),
            'customer_id' => $this->customer_id,
            'discount_percent' => $this->discount_percent,
            'discount_amount' => $this->discount_amount,
            'tax_percentage' => $this->tax_percentage,
            'tax_amount' => $this->tax_amount,
            $this->mergeWhen($this->relationLoaded('customer'), [
                'customer_name' => $this->customer->name
            ]),
            'contents' => OrderContentResource::collection($this->whenLoaded('contents')),
            $this->mergeWhen($this->relationLoaded('contents'), [
                'sub_total' => $this->contents->reduce($subTotalFn, 0),
                'grand_total' => ($this->contents->reduce($subTotalFn, 0) * (1 + $delta))
                    + $this->tax_amount - $this->discount_amount,
                'count' => $this->contents->reduce($countFn, 0),
            ]),
            'commission' => $this->commission,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
