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
            'route' => $request->route()->getName(),
            $this->mergeWhen(($request->route()->getName() === 'customers.index'), [
                'grand_total' => floatval($this->grand_total),
                'payment_total' => floatval($this->payment_total),
                'balance' => floatval($this->balance),
                'num_orders' => floatval($this->num_orders),
            ])
        ];
    }
}
