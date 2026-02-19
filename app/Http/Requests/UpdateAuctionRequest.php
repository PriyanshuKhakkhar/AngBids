<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAuctionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $auction = $this->route('auction');
        if (!$auction instanceof \App\Models\Auction) {
            $auction = \App\Models\Auction::find($this->route('id') ?? $this->id);
        }

        // Only allow owner to update
        return auth()->check() && auth()->id() === $auction->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $auction = $this->route('auction');
        if (!$auction instanceof \App\Models\Auction) {
            $auction = \App\Models\Auction::find($this->route('id') ?? $this->id);
        }

        $hasBids = $auction->bids()->exists();

        $rules = [
            'title' => ['sometimes', 'string', 'min:3', 'max:100'],
            'description' => ['sometimes', 'string', 'min:20', 'max:5000'],
            'category_id' => ['sometimes', 'exists:categories,id'],
            'start_time' => ['sometimes', 'date'],
            'end_time' => ['sometimes', 'date', 'after:start_time'],
            'min_increment' => ['sometimes', 'numeric', 'min:0.01', 'max:1000.00'],
            'specifications' => ['nullable', 'array'],
        ];

        // Only allow price/category change if no bids exist
        if (!$hasBids) {
            $rules['starting_price'] = ['sometimes', 'numeric', 'min:0.01', 'max:999999999'];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'end_time.after' => 'The auction end time must be after the start time.',
            'starting_price.min' => 'Starting price must be at least $0.01.',
        ];
    }
}
