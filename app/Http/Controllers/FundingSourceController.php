<?php

namespace App\Http\Controllers;

use App\Models\FundingSource;
use Illuminate\Http\Request;

class FundingSourceController extends Controller
{
    /**
     * Store a newly created funding source.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:190|unique:funding_sources,name',
            'code' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);

        FundingSource::create([
            'name' => trim($request->name),
            'code' => $request->code ? trim($request->code) : null,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return redirect()->route('settings.index', ['tab' => 'fiscal_budget'])
            ->with('success', 'Funding source added successfully!');
    }

    /**
     * Update the specified funding source.
     */
    public function update(Request $request, $id)
    {
        $fundingSource = FundingSource::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:190|unique:funding_sources,name,' . $fundingSource->id,
            'code' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);

        $fundingSource->update([
            'name' => trim($request->name),
            'code' => $request->code ? trim($request->code) : null,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : false,
        ]);

        return redirect()->route('settings.index', ['tab' => 'fiscal_budget'])
            ->with('success', 'Funding source updated successfully!');
    }

    /**
     * Remove the specified funding source (with linked projects protection).
     */
    public function destroy($id)
    {
        $fundingSource = FundingSource::withCount('projects')->findOrFail($id);

        if ($fundingSource->projects_count > 0) {
            return redirect()->route('settings.index', ['tab' => 'fiscal_budget'])
                ->with('error', "Cannot delete Funding Source '{$fundingSource->name}': {$fundingSource->projects_count} project(s) are attached.");
        }

        $fundingSource->delete();

        return redirect()->route('settings.index', ['tab' => 'fiscal_budget'])
            ->with('success', 'Funding source deleted successfully!');
    }
}

