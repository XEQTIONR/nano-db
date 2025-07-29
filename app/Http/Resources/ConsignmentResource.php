<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'exchange_rate' => $this->exchange_rate,
            'tax' => $this->tax,
            'land_date' => $this->land_date->toDateString(),
            'lc_num' => $this->lc,
            $this->mergeWhen($this->relationLoaded('letterOfCredit'), [
                'currency_code' => $this->letterOfCredit->currency_code,
            ]),
            'created_at' => $this->created_at->toDateTimeString(),
            
        ];
    }
}
