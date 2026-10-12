<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Models\Directorate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $query = Department::query();

        $user = Auth::user();
        if ($user->hasRole('directorate_admin')) {
            $query =  $query->whereHas('directorate', function ($query) use ($user) {
                $query->where('directorate_id', $user->personnel?->directorate_id);
            });
        }

        $departments = $query->select('ulid', 'name', 'code', 'function', 'directorate_id')->with('directorate')->orderBy('created_at', 'desc')->paginate(10);

        return view('department.index', ['departments' => $departments]);
    }


    /**
     * Display the specified resource.
     */
    public function show($ulid)
    {
        $user = Auth::user();

        $department = Department::query()->where('ulid', $ulid)->when($user->hasRole('directorate_admin'), function ($query) use ($user) {
            $query->whereHas('directorate', function ($query) use ($user) {
                $query->where('directorate_id', $user->personnel?->directorate_id);
            });
        })->firstOrFail();

        return view('department.show', compact('department'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $directorates = Directorate::select('id', 'name')->get();
        return view('department.create', ['directorates' => $directorates]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request)
    {
        DB::beginTransaction();

        try {

            $validated = $request->validated();

            $validated['created_by_id'] = Auth::id();

            Department::create($validated);

            DB::commit();

            return redirect()->route('departments.index')->with('success', 'Department created successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Department creation failed: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'An error occurred');
        }
    }


    public function edit($ulid)
    {
        $department = Department::where('ulid', $ulid)->firstOrFail();

        $directorates = Directorate::select('id', 'name')->get();

        return view('department.edit', ['department' => $department, 'directorates' => $directorates]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, $ulid)
    {
        $department = Department::where('ulid', $ulid)->firstOrFail();
        $validated = $request->validated();
        $validated['created_by_id'] = Auth::id();
        DB::beginTransaction();

        try {
            $department->update($validated);

            DB::commit();

            return redirect()->route('departments.index', $department->ulid)->with('success', 'Department update successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Department creation failed: ' . $e->getMessage());

            return redirect()->route('departments.edit', $department->ulid)->with('error', 'Department creation failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ulid)
    {
        $department = Department::where('ulid', $ulid)->firstOrFail();

        $department->update(['deleted_by' => Auth::id()]);

        $department->delete();

        return redirect()->back()->with('error', 'Department record deleted successfully.');
    }
}
