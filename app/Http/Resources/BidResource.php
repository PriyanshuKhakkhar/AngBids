<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BidResource extends JsonResource
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
            'amount' => $this->amount,
            'user' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
            ],
            'auction_id' => $this->auction_id,
            'created_at' => $this->created_at,
        ];
    }
}
