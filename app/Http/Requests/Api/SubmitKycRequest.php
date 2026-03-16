<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class SubmitKycRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name'     => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'address'       => 'required|string',
            'id_type'       => 'required|in:aadhaar,pan,passport,driving_license',
            'id_number'     => 'required|string|max:100',
            'id_document'   => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'selfie_image'  => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
