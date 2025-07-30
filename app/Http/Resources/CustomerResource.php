<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'created_at' => $this->created_at->toDateTimeString(),
            'orders' =>  OrderResource::collection($this->whenLoaded('orders')),
            // $this->mergeWhen($route == 'customers.index', [
            //     //'orders_total' => $this->orders->reduce(fn($carry, $order) => $carry + $order->grand_total ,0)
            //     'orders_total' => $this->orders
            // ]),
            
        ];
    }
}
