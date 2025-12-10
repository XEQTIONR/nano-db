<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContainerContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
           'container_num' => $this->Container_num,
           'bol' => $this->BOL,
           'tyre_id' => $this->tyre_id,
           'id' => $this->tyre_id,
           'qty' => $this-> qty,
           'unit_price' => $this->unit_price, 
           'total' => $this->total,
           'total_tax' => $this->total_tax,
           'total_weight' => $this->total_weight,
           $this->mergeWhen($this->relationLoaded('tyre'), [
                'tyre' => new TyreResource($this->tyre),
                'brand' => $this->tyre->brand,
                'size' => $this->tyre->size,
                'pattern' => $this->tyre->pattern,
                'lisi' => $this->tyre->lisi,
           ])
        ];
    }
}
