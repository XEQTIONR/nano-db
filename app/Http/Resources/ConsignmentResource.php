<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ConsignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'bol' => $this->BOL,
            'value' => $this->value,
            'value_local' => $this->value_local,
            'exchange_rate' => $this->exchange_rate,
            'tax' => $this->tax,
            'land_date' => ($this->land_date instanceof Carbon)
                ? $this->created_at->toDateString()
                : (new Carbon($this->created_at))->toDateString(),
            'lc_num' => $this->lc,
            $this->mergeWhen($this->relationLoaded('letterOfCredit'), [
                'currency_code' => $this->letterOfCredit->currency_code,
            ]),
            'created_at' => ($this->created_at instanceof Carbon)
                ? $this->created_at->toDateTimeString()
                : (new Carbon($this->created_at))->toDateTimeString(),
            'toString' => $this->BOL,
            $this->mergeWhen($this->relationLoaded('containers'), [
                'containers' => ContainerResource::collection($this->containers)
            ]),
            
        ];
    }
}
