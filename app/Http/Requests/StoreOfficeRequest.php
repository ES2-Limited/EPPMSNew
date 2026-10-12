<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOfficeRequest extends FormRequest
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
            'name' => 'required|string',
            'office_type_id' => 'required|exists:office_types,id',
            'office_region_id' => 'nullable|exists:regions,id',
            'lga_id' => 'required|exists:lgas,id',
            'address' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
        ];
    }
}
