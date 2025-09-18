<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
            'container_num' => $this->Container_num ?? $this->container_num,
            'bol' => $this->BOL ?? $this->bol,
            'land_date' => (new Carbon($this->land_date))->toDateString(),
            'lc_num' => $this->lc,
            'created_at' => $this->created_at->toDateTimeString(),
            'toString' => $this->container_num,

            $this->mergeWhen($this->relationLoaded('contents'), [
                'contents' => ContainerContentResource::collection($this->contents), // Change this to own resource
            ])
        ];
    }
}
