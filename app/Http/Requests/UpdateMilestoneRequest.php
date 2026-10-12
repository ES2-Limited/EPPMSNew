<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMilestoneRequest extends FormRequest
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
            'project_ulid' => 'required|exists:projects,ulid',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'cost_type' => 'nullable|in:payable,non-payable',
            'amount' => 'nullable|numeric|min:1',
            'deadline' => 'required|date',
        ];
    }
}
