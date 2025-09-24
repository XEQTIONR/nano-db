<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
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
        $isAdmin = Auth::user()->admin;
        return [
            'id' => $this->id,
            'expensable_type' => $this->expensable_type,
            'expensable_id' => $this->expensable_id,
            'date' => $this->date->toDateString(),
            'currency_code' => ($this->redacted && !$isAdmin) ? null : $this->currency_code,
            'note' => ($this->redacted && !$isAdmin) ? null : $this->note,
            'amount' => ($this->redacted && !$isAdmin) ? null : $this->amount,
            'amount_local' => ($this->redacted && !$isAdmin) ? null : $this->amount_local,
            'created_at' => $this->created_at->format('j M Y g:i a'),
            'toString' => '' . $this->id,
            'redacted' => $isAdmin ? false : $this->redacted,
        ];
    }
}
