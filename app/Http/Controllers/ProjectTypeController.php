<?php

namespace App\Http\Controllers;

use App\Models\ProjectType;
use Illuminate\Http\Request;

class ProjectTypeController extends Controller
{
    /**
     * Store a newly created project type.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150|unique:project_types,name',
        ]);

        ProjectType::create([
            'name' => trim($request->name),
        ]);

        return redirect()->route('settings.index', ['tab' => 'project_types'])
            ->with('success', 'Project type added successfully!');
    }

    /**
     * Update the specified project type.
     */
    public function update(Request $request, $id)
    {
        $projectType = ProjectType::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:150|unique:project_types,name,' . $projectType->id,
        ]);

        $projectType->update([
            'name' => trim($request->name),
        ]);

        return redirect()->route('settings.index', ['tab' => 'project_types'])
            ->with('success', 'Project type updated successfully!');
    }

    /**
     * Remove the specified project type with relationship check.
     */
    public function destroy($id)
    {
        $projectType = ProjectType::withCount('projects')->findOrFail($id);

        if ($projectType->projects_count > 0) {
            return redirect()->route('settings.index', ['tab' => 'project_types'])
                ->with('error', "Cannot delete '{$projectType->name}': {$projectType->projects_count} project(s) are currently categorized under this type.");
        }

        $projectType->delete();

        return redirect()->route('settings.index', ['tab' => 'project_types'])
            ->with('success', 'Project type deleted successfully!');
    }
}
