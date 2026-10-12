<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonnelRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'other_name' => 'nullable|string|max:255',
            'office_id' => 'required|exists:offices,id',
            // 'unit_id' => 'required|exists:units,id',
            'department_id' => 'required|exists:departments,id',
            'address' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'phone' => 'nullable|string',
        ];
    }
}
