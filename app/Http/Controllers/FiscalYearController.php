<?php

namespace App\Http\Controllers;

use App\Models\FiscalYear;
use Illuminate\Http\Request;

class FiscalYearController extends Controller
{
    /**
     * Store a newly created fiscal year.
     */
    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|string|max:20|unique:fiscal_years,year',
            'description' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        FiscalYear::create([
            'year' => trim($request->year),
            'description' => $request->description ? trim($request->description) : null,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return redirect()->route('settings.index', ['tab' => 'fiscal_budget'])
            ->with('success', 'Fiscal Year created successfully!');
    }

    /**
     * Update the specified fiscal year.
     */
    public function update(Request $request, $id)
    {
        $fiscalYear = FiscalYear::findOrFail($id);

        $request->validate([
            'year' => 'required|string|max:20|unique:fiscal_years,year,' . $fiscalYear->id,
            'description' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $fiscalYear->update([
            'year' => trim($request->year),
            'description' => $request->description ? trim($request->description) : null,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : false,
        ]);

        return redirect()->route('settings.index', ['tab' => 'fiscal_budget'])
            ->with('success', 'Fiscal Year updated successfully!');
    }

    /**
     * Remove the specified fiscal year (with linked projects protection).
     */
    public function destroy($id)
    {
        $fiscalYear = FiscalYear::withCount('projects')->findOrFail($id);

        if ($fiscalYear->projects_count > 0) {
            return redirect()->route('settings.index', ['tab' => 'fiscal_budget'])
                ->with('error', "Cannot delete Fiscal Year '{$fiscalYear->year}': {$fiscalYear->projects_count} project(s) are linked to this year.");
        }

        $fiscalYear->delete();

        return redirect()->route('settings.index', ['tab' => 'fiscal_budget'])
            ->with('success', 'Fiscal Year deleted successfully!');
    }
}

