<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class CustomerSqlResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $route = $request->route()->getName();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone,
            'notes' => $this->notes,
            'created_at' => ($this->created_at instanceof Carbon)
                ? $this->created_at->format('j M Y g:i a')
                : (new Carbon($this->created_at))->format('j M Y g:i a'),
            'route' => $request->route()->getName(),
            $this->mergeWhen((
                in_array($request->route()->getName(), ['customers.index', 'customers.edit'])
            ), [
                'grand_total' => floatval($this->grand_total),
                'payment_total' => floatval($this->payment_total),
                'balance' => floatval($this->balance),
                'num_orders' => floatval($this->num_orders),
                'total_commission' => floatval($this->total_commission)
            ]),
            // 'orders' => OrderResource::collection($this->whenLoaded('orders'))
        ];
    }
}
