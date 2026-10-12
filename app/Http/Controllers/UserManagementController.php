<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('q');
        $users = User::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('users.index', compact('users', 'search'));
    }

    public function create()
    {
        $user = new User();
        $roles = Role::where('role_category_id', 1)->select('id', 'name')->get();
        return view('users.form', compact('user', 'roles'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:8'],
            'role_id' => 'required|exists:roles,id',
        ]);

        $password = $validated['password'] ?? Str::password(12);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($password),
        ]);

        $role = Role::where('id', $validated['role_id'])->first();

        $user->assignRole($role->name);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }

    public function edit(User $user)
    {
        $roles = Role::where('role_category_id', 1)->select('id', 'name')->get();
        return view('users.form', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'  => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role_id' => 'required|exists:roles,id',
        ]);

        if (!empty($validated['name'])) {
            $user->name = $validated['name'];
        }
        $user->email = $validated['email'];
        $user->save();

        $role = Role::find($validated['role_id']);

        if ($user) {
            $user->syncRoles([$role->name]);
        }

        return back()->with('success', 'User updated successfully.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($request->user()->id === $user->id) {
            return back()->with('error', 'You cannot delete your own account from this screen.');
        }

        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }
}
