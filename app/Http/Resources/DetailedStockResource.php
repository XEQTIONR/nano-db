<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailedStockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'tyre_id' => $this->tyre_id,
            'brand' => $this->brand,
            'size' => $this->size,
            'lisi' => $this->lisi,
            'pattern' => $this->pattern,
            'container_num' => $this->container_num,
            'bol' => $this->bol,
            'ordered_qty' => intval($this->ordered_qty),
            'supplied_qty' => intval($this->supplied_qty),
            'in_stock' => intval($this->in_stock),
            'created_at' => $this->created_at->format('j M Y g:i a'),
        ];
    }
}
