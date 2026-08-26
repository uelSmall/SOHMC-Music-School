<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function create()
    {
        $roles = Role::with('permissions')->get();

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|min:2|max:191',
            'last_name' => 'required|string|min:2|max:191',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(6)],
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ]);

        $validated['name'] = trim($validated['first_name'] . ' ' . $validated['last_name']);
        $validated['password'] = Hash::make($validated['password']);
        $validated['email_verified_at'] = now();

        // Remove roles from data — not a users table column, managed via Spatie syncRoles()
        $roles = $validated['roles'] ?? [];
        unset($validated['roles']);

        $user = User::create($validated);

        if (! empty($roles)) {
            $user->syncRoles($roles);
        }

        return redirect()->route('admin.users.index')->with('status', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::with('permissions')->get();
        $userRoles = $user->roles->pluck('name')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|min:2|max:191',
            'last_name' => 'required|string|min:2|max:191',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::min(6)],
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ]);

        $validated['name'] = trim($validated['first_name'] . ' ' . $validated['last_name']);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Remove roles from data — not a users table column, managed via Spatie syncRoles()
        $roles = $validated['roles'] ?? [];
        unset($validated['roles']);

        $user->update($validated);
        $user->syncRoles($roles);

        return redirect()->route('admin.users.index')->with('status', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot delete yourself.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'User deleted successfully.');
    }
}
