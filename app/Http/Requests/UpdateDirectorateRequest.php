<?php

namespace App\Http\Requests;

use App\Models\Directorate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDirectorateRequest extends FormRequest
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
        $directorate = Directorate::where('ulid', $this->directorate)->first();
        return [
            'code' => ['sometimes', 'string', 'max:255', Rule::unique('directorates', 'code')->ignore($directorate->ulid, 'ulid')],
            'name' => 'required|string',
            'function' => 'nullable|string',
        ];
    }
}
