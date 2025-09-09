<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WasteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'container_num' => $this->Container_num,
            'bol' => $this->BOL,
            'tyre_id' => $this->tyre_id,
            'qty' => $this->qty,
            'tyre' => (new TyreResource($this->whenLoaded('tyre'))),
            $this->mergeWhen($this->relationLoaded('tyre'), fn() => [
                'brand' => $this->tyre->brand,
                'size' => $this->tyre->size,
                'pattern' => $this->tyre->pattern,
                'lisi' => $this->tyre->lisi,
            ]),
            'created_at' => $this->created_at->toDateTimeString()
        ];
    }
}
