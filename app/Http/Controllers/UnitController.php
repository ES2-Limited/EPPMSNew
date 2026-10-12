<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $query = Unit::query()->when($user->hasRole('directorate_admin'), function ($query) use ($user) {
            $query->whereHas('department', function ($query) use ($user) {
                $query->where('directorate_id', $user->personnel?->directorate_id);
            });
        })->when($user->hasRole('head_of_department'), function ($query) use ($user) {
            $query->whereHas('department', function ($query) use ($user) {
                $query->where('directorate_id', $user->personnel?->deparment_id);
            });
        });

        $units = $query->select('ulid', 'name', 'function', 'department_id')->with('department')->orderBy('created_at', 'desc')->paginate(10);

        return view('units.index')->with(['units' => $units]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::select('id', 'name')->get();
        return view('units.create')->with('departments', $departments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnitRequest $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validated();

            $validated['created_by_id'] = Auth::id();

            Unit::create($validated);

            DB::commit();

            return redirect()->route('units.index')->with('success', 'Unit created successfully');
        } catch (\Exception $e) {

            Log::error('Unit creation failed: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'An error occurred');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($ulid)
    {

        $user = Auth::user();

        $unit = Unit::query()->where('ulid', $ulid)->when($user->hasRole('directorate_admin'), function ($query) use ($user) {
            $query->whereHas('department', function ($query) use ($user) {
                $query->where('directorate_id', $user->personnel?->directorate_id);
            });
        })->firstOrFail();

        return view('units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($ulid)
    {
        $unit = Unit::where('ulid', $ulid)->firstOrFail();

        $departments = Department::select('id', 'name')->get();
        return view('units.edit', ['unit' => $unit, 'departments' => $departments]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUnitRequest $request, $ulid)
    {
        try {
            $validated = $request->validated();
            $validated['created_by_id'] = auth()->id();

            DB::beginTransaction();


            $unit = Unit::where('ulid', $ulid)->firstOrFail();

            $unit->update($validated);


            DB::commit();

            return redirect()->route('units.index')->with('success', 'Unit updated successfully');

        } catch (\Exception $e) {

            Log::error('Unit update failed: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'An error occurred');
        }
    }

    public function destroy($ulid)
    {
        $unit = Unit::where('ulid', $ulid)->firstOrFail();

        $unit->update(['deleted_by' => Auth::id()]);

        $unit->delete();

        return redirect()->back()->with('error', 'Unit record deleted successfully.');
    }
}
