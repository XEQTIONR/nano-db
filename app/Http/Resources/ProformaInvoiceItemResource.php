<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProformaInvoiceItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->tyre_id,
            'lc_num' => $this->lc_num,
            'qty' => intval($this->qty),
            'unit_price' => floatval($this->unit_price),
            $this->mergeWhen($this->relationLoaded('tyre'), [
                'brand' => $this->tyre->brand,
                'size' => $this->tyre->size,
                'pattern' => $this->tyre->pattern,
                'lisi' => $this->tyre->lisi,
            ]),
        ];
    }
}
x