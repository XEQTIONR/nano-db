<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContainerResource extends JsonResource
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
            $this->mergeWhen($this->relationLoaded('consignment'), [
                'land_date' => $this->consignment->land_date->toDateString(),
                'lc_num' => $this->consignment->lc
            ]),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
