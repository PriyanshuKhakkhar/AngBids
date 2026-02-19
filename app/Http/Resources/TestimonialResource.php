<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TestimonialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'role' => $this->role,
            'avatar' => $this->avatar_url ? asset('storage/' . $this->avatar_url) : null,
            'message' => $this->content,
            'created_at' => $this->created_at->format('Y-m-d'),
        ];
    }
}
