<?php

namespace App\Http\Controllers;

use App\Models\FirmType;
use Illuminate\Http\Request;

class FirmTypeController extends Controller
{
    /**
     * Store a newly created firm type.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150|unique:firm_types,name',
        ]);

        FirmType::create([
            'name' => trim($request->name),
        ]);

        return redirect()->route('settings.index', ['tab' => 'firm_types'])
            ->with('success', 'Firm classification added successfully!');
    }

    /**
     * Update the specified firm type.
     */
    public function update(Request $request, $id)
    {
        $firmType = FirmType::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:150|unique:firm_types,name,' . $firmType->id,
        ]);

        $firmType->update([
            'name' => trim($request->name),
        ]);

        return redirect()->route('settings.index', ['tab' => 'firm_types'])
            ->with('success', 'Firm classification updated successfully!');
    }

    /**
     * Remove the specified firm type.
     */
    public function destroy($id)
    {
        $firmType = FirmType::withCount('contractors')->findOrFail($id);

        if ($firmType->contractors_count > 0) {
            return redirect()->route('settings.index', ['tab' => 'firm_types'])
                ->with('error', "Cannot delete '{$firmType->name}': {$firmType->contractors_count} firm(s) are currently attached to this classification.");
        }

        $firmType->delete();

        return redirect()->route('settings.index', ['tab' => 'firm_types'])
            ->with('success', 'Firm classification deleted successfully!');
    }
}

