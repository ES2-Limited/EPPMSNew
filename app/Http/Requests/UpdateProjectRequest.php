<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
            'directorate_id' => 'required|exists:directorates,id',
            'contractor_id' => 'required|exists:contractors,id',
            'consultant_id' => 'nullable|exists:contractors,id',
            'cost' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'duration' => 'required|string|min:1',
            'duration_period' => 'required|string',
            'office_id' => 'required|exists:offices,id',
            'award_date' => 'required|date',
            'deadline' => 'sometimes|date|after:award_date',
            'award_letter' => 'nullable|mimes:jpeg,png,jpg,pdf,doc,docx|max:2048'
        ];
    }
}
