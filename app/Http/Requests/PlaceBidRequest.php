<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlaceBidRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // User must be logged in to bid
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $auction = $this->route('auction');
        $minIncrement = $auction->min_increment ?? 0.01;
        $maxIncrement = \App\Models\Auction::MAX_INCREMENT_ALLOWED;

        return [
            'increment' => [
                'nullable',
                'required_without:max_bid_amount',
                'numeric',
                'min:' . $minIncrement,
                'max:' . $maxIncrement,
            ],
            'max_bid_amount' => [
                'nullable',
                'required_without:increment',
                'numeric',
                'min:' . ($auction->current_price + $minIncrement),
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'increment.required_without' => 'Please enter either a bid increment or a maximum bid amount.',
            'max_bid_amount.required_without' => 'Please enter either a bid increment or a maximum bid amount.',
            'increment.numeric' => 'The bid increment must be a valid number.',
            'increment.min' => 'Your increment is too small. The minimum allowed is ₹' . number_format($this->route('auction')->min_increment ?? 0.01, 2) . '.',
            'increment.max' => 'Your increment is too large. The maximum jump allowed in a single bid is ₹' . number_format(\App\Models\Auction::MAX_INCREMENT_ALLOWED, 2) . '.',
            'max_bid_amount.numeric' => 'The maximum bid amount must be a valid number.',
            'max_bid_amount.min' => 'Your maximum bid must be at least ₹' . number_format(($this->route('auction')->current_price + ($this->route('auction')->min_increment ?? 0.01)), 2) . '.',
        ];
    }
}
