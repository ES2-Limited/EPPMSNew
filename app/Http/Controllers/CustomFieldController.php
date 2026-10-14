<?php

namespace App\Http\Controllers;

use App\Models\CustomField;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomFieldController extends Controller
{
    /**
     * Store a newly created custom field.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'model_type' => 'required|string|in:Project,Contractor,Milestone',
            'field_type' => 'required|string|in:text,number,date,select,textarea',
            'options' => 'nullable|string',
            'is_required' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $baseSlug = Str::slug($request->name, '_');
        $slug = $baseSlug;
        $counter = 1;
        while (CustomField::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '_' . $counter++;
        }

        $field = CustomField::create([
            'model_type' => $request->model_type,
            'name' => trim($request->name),
            'slug' => $slug,
            'field_type' => $request->field_type,
            'options' => $request->options ? trim($request->options) : null,
            'is_required' => $request->has('is_required'),
            'is_active' => $request->has('is_active'),
        ]);

        AuditService::log('created', $field, "Created custom field '{$field->name}' for {$field->model_type}", null, $field->toArray());

        return redirect()->route('settings.index', ['tab' => 'custom_fields'])
            ->with('success', "Custom field '{$field->name}' created successfully!");
    }

    /**
     * Update the specified custom field.
     */
    public function update(Request $request, $id)
    {
        $field = CustomField::findOrFail($id);
        $oldValues = $field->toArray();

        $request->validate([
            'name' => 'required|string|max:150',
            'field_type' => 'required|string|in:text,number,date,select,textarea',
            'options' => 'nullable|string',
            'is_required' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $field->update([
            'name' => trim($request->name),
            'field_type' => $request->field_type,
            'options' => $request->options ? trim($request->options) : null,
            'is_required' => $request->has('is_required'),
            'is_active' => $request->has('is_active'),
        ]);

        AuditService::log('updated', $field, "Updated custom field '{$field->name}'", $oldValues, $field->toArray());

        return redirect()->route('settings.index', ['tab' => 'custom_fields'])
            ->with('success', "Custom field '{$field->name}' updated successfully!");
    }

    /**
     * Remove the specified custom field.
     */
    public function destroy($id)
    {
        $field = CustomField::findOrFail($id);
        $oldValues = $field->toArray();
        $name = $field->name;

        $field->delete();

        AuditService::log('deleted', CustomField::class, "Deleted custom field '{$name}'", $oldValues, null);

        return redirect()->route('settings.index', ['tab' => 'custom_fields'])
            ->with('success', "Custom field '{$name}' deleted successfully!");
    }
}

