<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemReturnResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_num' => $this->order_num,
            'tyre_id' => $this->tyre_id,
            'bol' => $this->bol,
            'container_num' => $this->container_num,
            'unit_price' => $this->unit_price,
            'qty' => $this->qty,
            'created_at' => $this->created_at->format('j M Y g:i a'),

            $this->mergeWhen($this->relationLoaded('tyre'), fn () => [
                'id' => $this->tyre_id,
                'brand' => $this->tyre->brand,
                'size' => $this->tyre->size,
                'pattern' => $this->tyre->pattern,
                'lisi' => $this->tyre->lisi,
                'tyre' => new TyreResource($this->tyre)
            ]),
        ];
    }
}
