<?php

namespace App\Http\Requests;

use App\Models\Department;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends FormRequest
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
        $department = Department::where('ulid', $this->department)->first();
        return [
            'directorate_id' => 'required|string|max:255|exists:directorates,id',
            'code' => ['sometimes', 'string', 'max:255', Rule::unique('departments', 'code')->ignore($department->ulid, 'ulid')],
            'name' => 'required|string',
            'function' => 'nullable|string',
        ];
    }
}
