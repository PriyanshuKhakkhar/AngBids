<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuctionResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'starting_price' => $this->starting_price,
            'current_price' => $this->current_price,
            'status' => $this->status,

            'user' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
            ],

            'category' => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
                'slug' => $this->category?->slug,
            ],

            'images' => $this->images->map(function ($image) {
            return [
                'id' => $image->id,
                'url' => $image->url
                ];
            }),

            'bids' => $this->bids->map(function ($bid) {
            return [
                'id' => $bid->id,
                'amount' => $bid->amount,
                'user' => [
                    'id' => $bid->user->id,
                    'name' => $bid->user->name
                ]
            ];
        }),

            'created_at' => $this->created_at,
        ];
    }
}
