<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderContentResource extends JsonResource
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
            'tyre_id' => $this->tyre_id,
            'container_num' => $this->container_num,
            'bol' => $this->bol,
            'qty' => $this->qty,
            'unit_price' => $this->unit_price,
            'item_total' => $this->item_total,
            'tyre' => $this->whenLoaded('tyre'),
        ];
    }
}
