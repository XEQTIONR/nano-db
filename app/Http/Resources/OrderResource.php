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
        $paymentsTotalFn = fn(float $carry, $payment) => ($carry + $payment->amount);

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
            'contents' => OrderContentResource::collection($this->whenLoaded('contents')),
            'customer' => (new CustomerSqlResource($this->whenLoaded('customer'))),
            $this->mergeWhen($this->relationLoaded('customer'), fn() => [
                'customer_name' => $this->customer->name,
            ]),

            'returns' => OrderItemReturnResource::collection($this->whenLoaded('returns')),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
            $this->mergeWhen($this->relationLoaded('returns'), fn() => [ 
                'returns_consolidated' => OrderItemReturnResource::collection($this->returns
                    ->groupBy('tyre_id')
                    ->map(function($item, $tyreId) {
                        return $item->groupBy('unit_price')
                                ->map(function($item2, $unitPrice) {
                                    return $item2->reduce(function($carry, $item3) {
                                        if ($carry ==  null) {
                                            return $item3;
                                        }
                                        $carry->qty += $item3->qty;
                                        return $carry;
                                    }, null);
                                })
                        ;
                    })
                    ->collapse()
                    ->values())
            ]),

            $this->mergeWhen($this->relationLoaded('payments'), fn() => [ 
                'count_payments' => $this->payments->count(),
                'payments_total' => $this->payments->reduce($paymentsTotalFn, 0),
            ]),

            $this->mergeWhen(
                $this->relationLoaded('contents') && $this->relationLoaded('payments'), fn() => [
                    // 'kontentsReduce' => $this->items->reduce($subTotalFn, 0),
                     
                    'balance' => ($this->contents->reduce($subTotalFn, 0) * (1 + $delta))
                        + $this->tax_amount 
                        - $this->discount_amount 
                        - $this->commission
                        - $this->payments->reduce($paymentsTotalFn, 0),
                ]
            ),

            'commission' => $this->commission,
            'expenses' => ExpenseResource::collection($this->whenLoaded('expenses')),
            'random' => $this->random,
            'created_at' => $this->created_at->toDateTimeString(),
            'toString' => $this->Order_num,

            $this->mergeWhen($this->relationLoaded('contents'), fn () => [
                'sub_total' => $this->contents->reduce($subTotalFn, 0),
                'grand_total' => ($this->contents->reduce($subTotalFn, 0) * (1 + $delta))
                    + $this->tax_amount - $this->discount_amount,
                'count' => $this->contents->reduce($countFn, 0),
                
                //'items' => [],
                'items' => collect($this->contents()->get())
                    ->groupBy(['tyre_id', fn($item) => $item['unit_price']], preserveKeys: true)
                    ->map(function($item, $tyreId) {
                        return $item->map(function($items, $price) {
                            $qty = $items->reduce(function($carry, $itm) {
                                return $carry + $itm['qty'];
                            }, 0);

                            $itum = $items->first();

                            $itum->qty = $qty;

                            return [
                                'order_num' => $itum->Order_num,
                                'tyre_id' => $itum->tyre_id,
                                'qty' => $itum->qty,
                                'unit_price' => $itum->unit_price,
                                'item_total' => $itum->item_total,
                                'tyre' => $itum->tyre,
                                'brand' => $itum->tyre->brand,
                                'size' => $itum->tyre->size,
                                'pattern' => $itum->tyre->pattern,
                                'lisi' => $itum->tyre->lisi
                            ];
                        });
                    })
                    ->values()
                    ->map(function($itm, $key) {
                        return $itm->values();
                    })
                    ->collapse()
                    ->filter(fn($value) => $value['qty'] > 0)
                    ->values(),
            ]),

        ];
    }
}
