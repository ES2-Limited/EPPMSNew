<?php

namespace App\Http\Controllers;

use App\Models\OfficeType;
use Illuminate\Http\Request;

class OfficeTypeController extends Controller
{
    /**
     * Store a newly created office type.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:office_types,name',
        ]);

        OfficeType::create([
            'name' => trim($request->name),
        ]);

        return redirect()->route('settings.index', ['tab' => 'offices'])
            ->with('success', 'Office type added successfully!');
    }

    /**
     * Update the specified office type.
     */
    public function update(Request $request, $id)
    {
        $officeType = OfficeType::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100|unique:office_types,name,' . $officeType->id,
        ]);

        $officeType->update([
            'name' => trim($request->name),
        ]);

        return redirect()->route('settings.index', ['tab' => 'offices'])
            ->with('success', 'Office type updated successfully!');
    }

    /**
     * Remove the specified office type if no offices are linked.
     */
    public function destroy($id)
    {
        $officeType = OfficeType::withCount('offices')->findOrFail($id);

        if ($officeType->offices_count > 0) {
            return redirect()->route('settings.index', ['tab' => 'offices'])
                ->with('error', "Cannot delete '{$officeType->name}': {$officeType->offices_count} office location(s) are currently attached to this type.");
        }

        $officeType->delete();

        return redirect()->route('settings.index', ['tab' => 'offices'])
            ->with('success', 'Office type deleted successfully!');
    }
}

