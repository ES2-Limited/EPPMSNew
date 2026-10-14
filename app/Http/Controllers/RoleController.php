<?php

namespace App\Http\Controllers;

use App\Constants\RoleAndPermissions;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with('permissions:id,name')
            ->select('id', 'name', 'created_at')
            ->paginate(15);

        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role with permissions.
     */
    public function create()
    {
        $modules = RoleAndPermissions::PERMISSION_MODULES;
        return view('roles.create', compact('modules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name|max:100',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        $role = Role::create([
            'name' => strtolower(trim($request->name)),
            'guard_name' => 'web',
        ]);

        if ($request->filled('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('roles.index')->with('success', 'Role created with permissions successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('roles.edit', $id);
    }

    /**
     * Show the form for editing the specified role and its permission matrix.
     */
    public function edit(string $id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $modules = RoleAndPermissions::PERMISSION_MODULES;
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.edit', compact('role', 'modules', 'rolePermissions'));
    }

    /**
     * Update the specified role name and permissions.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        if (strtolower($role->name) !== 'admin') {
            $role->name = strtolower(trim($request->name));
            $role->save();
        }

        // Sync selected permissions
        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('roles.index')->with('success', 'Role and permissions updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);

        if (strtolower($role->name) === 'admin') {
            return redirect()->back()->with('error', 'The Super Admin role cannot be deleted.');
        }

        if (User::role($role->name)->exists()) {
            return redirect()->back()->with('error', 'Cannot delete role: it is currently assigned to one or more active users.');
        }

        $role->delete();
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
