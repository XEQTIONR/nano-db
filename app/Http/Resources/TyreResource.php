<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TyreResource extends JsonResource
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
            'brand' => $this->brand,
            'size' => $this->size,
            'pattern' => $this->pattern,
            'lisi' => $this->lisi,
            'created_at' => $this->created_at->format('j M Y g:i a'),
            'in_stock' => $this->in_stock === null ? null : intval($this->in_stock),
        ];
    }
}
