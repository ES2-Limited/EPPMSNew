<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with('permissions:id,name')->where('name', '!=', 'ADMIN')->select('id', 'name', 'created_at')->paginate(10);
        $permissions = Permission::select('id', 'name')->get();
        return view('roles.index', ['roles' => $roles, 'permissions' => $permissions]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            "roles" => Role::with('permissions:id,name')->where('name', '!=', 'ADMIN')->select('id', 'name')->get(),
            "permissions" => Permission::select('id', 'name')->get()
        ];

        return view('roles.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $role = $request->only('name');
        $role = Role::create($role)->givePermissionTo($request->only('permissions'));

        if ($role) {
            return redirect()->route('roles.index')->with('success', 'Role created successfully');
        }
        return redirect()->route('roles.create')->withInput()->with('error', 'Role creation failed');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::where('id', $id)->select('id', 'name')->firstOrFail();
        return  view('roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:roles,name,'.$role->id,
            'permissions' => 'nullable|array'
        ]);

        $role->name = $request->name;
        $role->save();


        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::where('id', $id)->select('id', 'name')->firstOrFail();

        if (User::role($role->name)->exists()) {
            return redirect()->back()->with('error', 'Cannot delete role. It is assigned to one or more users.');
        }

        $usersWithRole = User::role($role->name)->get();
        foreach ($usersWithRole as $user) {
            $user->removeRole($role->name);
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
