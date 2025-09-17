<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ExpenseResource extends JsonResource
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
            'expensable_type' => $this->expensable_type,
            'expensable_id' => $this->expensable_id,
            'date' => $this->date->toDateString(),
            'currency_code' => $this->currency_code,
            'amount' => $this->amount,
            'amount_local' => $this->amount_local,
            'created_at' => $this->created_at->toDateTimeString(),
            'toString' => '' . $this->id,
            $this->mergeWhen(Str::of($this->expensable_type)->startsWith("App\\Models\\"), fn() => [
                'expensable' => $this->expensable
            ])
        ];
    }
}
