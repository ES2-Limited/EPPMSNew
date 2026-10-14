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
        $primaryTier = setting('project_primary_tier', 'directorate');
        $enableDirectorates = (bool)setting('enable_directorates', true);
        $enableDepartments = (bool)setting('enable_departments', true);
        $enableUnits = (bool)setting('enable_units', true);

        $directorateRule = ($enableDirectorates && $primaryTier === 'directorate') ? 'required|exists:directorates,id' : 'nullable|exists:directorates,id';
        $departmentRule = ($enableDepartments && $primaryTier === 'department') ? 'required|exists:departments,id' : 'nullable|exists:departments,id';
        $unitRule = ($enableUnits && $primaryTier === 'unit') ? 'required|exists:units,id' : 'nullable|exists:units,id';

        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'directorate_id' => $directorateRule,
            'department_id' => $departmentRule,
            'unit_id' => $unitRule,
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
