<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Office;
use App\Models\Personnel;
use App\Models\Department;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StorePersonnelRequest;
use App\Http\Requests\UpdatePersonnelRequest;
use App\Models\Directorate;
use App\Services\APIMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PersonnelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $personnels = Personnel::query()->select('ulid', 'designation', 'user_id', 'department_id', 'office_id', 'directorate_id')->when($user->hasRole('directorate_admin'), function ($query) use ($user) {
            $query->where('directorate_id', $user->personnel?->directorate_id);
        })->when($user->hasRole('head_of_department'), function ($query) use ($user) {
            $query->where('department_id', $user->personnel?->department_id);
        })
            ->with('user', 'department.directorate')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('personnels.index', ['personnels' => $personnels]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::select('id', 'name')->get();
        $directorates = Directorate::select('id', 'name')->get();
        $offices = Office::select('id', 'name')->get();
        $roles = Role::where('role_category_id', 1)->where('name', '!=', 'admin')->select('id', 'name')->get();

        return view('personnels.create', compact('directorates', 'departments', 'offices', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonnelRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {

            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'other_name' => $validated['other_name'] ?? '',
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->generateVerificationToken();

            $role = Role::where('id', $validated['role_id'])->first();

            $user->assignRole($role->name);

            Personnel::create([
                'user_id' => $user->id,
                'office_id' => $validated['office_id'],
                'directorate_id' => $validated['directorate_id'],
                'department_id' => $validated['department_id'] ?? null,
                'created_by_id' => Auth::id(),
                'phone' => $validated['phone'],
                'role_id' => $validated['role_id'],
            ]);

            DB::commit();

            $sendmail = new APIMail;
            $sendmail->sendMail($user);

            return redirect()->route('personnels.index')->with('success', 'Personnel created successfully.');
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Personnel creation failed: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'An error occurred');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($ulid)
    {
        $personnel = Personnel::where('ulid', $ulid)->firstOrFail();
        return view('personnels.show', compact('personnel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($ulid)
    {
        $personnel = Personnel::where('ulid', $ulid)->firstOrFail();
        $departments = Department::select('id', 'name')->get();
        $offices = Office::select('id', 'name')->get();
        return  view('personnels.edit', compact('personnel', 'departments', 'offices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonnelRequest $request, $ulid)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {

            $personnel = Personnel::where('ulid', $ulid)->firstOrFail();

            $user = $personnel->user;

            $user->first_name = $validated['first_name'];
            $user->last_name = $validated['last_name'];
            $user->other_name = $validated['other_name'] ?? '';
            $user->update();

            $validated['created_by_id'] = auth()->id();
            $personnel->update($validated);

            DB::commit();

            return redirect()->back()->with('success', 'Personnel updated successfully.');
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Personnel update failed: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'An error occurred');
        }
    }

    public function destroy($ulid)
    {
        $personnel = Personnel::where('ulid', $ulid)->firstOrFail();

        $personnel->update(['deleted_by' => Auth::id()]);

        $personnel->user?->delete();

        $personnel->delete();

        return redirect()->back()->with('error', 'Personnel record deleted successfully.');
    }
}
