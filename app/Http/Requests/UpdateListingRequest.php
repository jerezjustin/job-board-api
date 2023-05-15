<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateListingRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'string',
            'company' => 'string',
            'logo' => 'file|mimes:jpg,jpeg,png|max:2048',
            'location' => 'string',
            'apply_link' => 'url',
            'content' => 'string|max:20000',
            'payment_method_id' => 'string',
            'tags' => 'string'
        ];
    }
}
