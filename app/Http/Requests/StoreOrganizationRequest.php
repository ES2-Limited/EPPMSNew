<?php

namespace App\Http\Requests;

use App\Models\Organization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrganizationRequest extends FormRequest
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
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(Organization::class)],
            'name' => 'required|string',
            'website' => 'nullable|string',
            // 'address1' => 'required|string|max:50',
            // 'address2' => 'nullable|string|max:50',
            // 'lga_id' => 'required|string|max:25',
            // 'contact_no' => 'required|string|max:15',
            // 'purpose' => 'required|string',
            // 'vision' => 'nullable|string',
            // 'mission' => 'nullable|string',
            // 'motto' => 'nullable|string|max:50',
            // 'brief' => 'nullable|string',
            // 'bldg' => 'nullable|file|mimes:jpeg,png,pdf,doc,docx|max:2048',
            // 'logo' => 'nullable|file|mimes:jpeg,png|max:2048',
            // 'branch' => 'required|boolean',
        ];
    }
}
