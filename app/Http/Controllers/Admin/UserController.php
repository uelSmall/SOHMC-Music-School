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
        $parents = User::role('parent')->orderBy('name')->get();

        return view('admin.users.create', compact('roles', 'parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|min:2|max:191',
            'last_name' => 'required|string|min:2|max:191',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::min(6)],
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
            'parents' => 'nullable|array',
            'parents.*' => 'exists:users,id',
        ]);

        $validated['name'] = trim($validated['first_name'] . ' ' . $validated['last_name']);
        $validated['password'] = Hash::make($validated['password']);
        $validated['email_verified_at'] = now();

        // Remove roles from data — not a users table column, managed via Spatie syncRoles()
        $roles = $validated['roles'] ?? [];
        unset($validated['roles']);

        $parents = $validated['parents'] ?? [];
        unset($validated['parents']);

        $user = User::create($validated);

        // Raw pivot writes — Spatie's syncRoles can silently fail on the live
        // schema (model_has_roles has no guard_name column), leaving a brand
        // new user with no roles (AGENTS.md daybook, Sep 2026).
        if (! empty($roles)) {
            foreach ($roles as $roleName) {
                $roleId = \DB::table('roles')->where('name', $roleName)->value('id');
                if ($roleId) {
                    \DB::table('model_has_roles')->insert([
                        'role_id' => $roleId,
                        'model_type' => User::class,
                        'model_id' => $user->id,
                    ]);
                }
            }

            $user->clearPermissionCache();
        }

        // Students get a permanent student number, e.g. SOHMC-2026-0001
        if (in_array('student', $roles, true)) {
            $user->assignStudentNumber();
        }

        $user->parents()->sync($parents);

        return redirect()->route('admin.users.index')->with('status', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::with('permissions')->get();
        // Read roles straight from the pivot table — the presenter's cached
        // accessor can serve stale role data after a role change (AGENTS.md).
        $userRoles = \DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_id', $user->id)
            ->where('model_has_roles.model_type', User::class)
            ->pluck('roles.name')
            ->toArray();
        $parents = User::role('parent')->orderBy('name')->get();
        $userParents = $user->parents()->pluck('users.id')->map(fn ($id) => (int) $id)->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'userRoles', 'parents', 'userParents'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|min:2|max:191',
            'last_name' => 'required|string|min:2|max:191',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'mobile' => 'nullable|string|max:20',
            'password' => ['nullable', 'confirmed', Password::min(6)],
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
            'parents' => 'nullable|array',
            'parents.*' => 'exists:users,id',
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

        // Email verification toggle — "Mark as verified" checkbox on the edit form.
        // Checkbox unchecked = absent, so a verified user stays verified because the
        // form renders it checked unless the admin deliberately unchecks it.
        $validated['email_verified_at'] = $request->boolean('verified') ? now() : null;

        $parents = $validated['parents'] ?? [];
        unset($validated['parents']);

        $user->update($validated);

        // Roles are written straight to the pivot table. Spatie's syncRoles can
        // silently detach roles without re-attaching on the live schema
        // (model_has_roles has no guard_name column), leaving a user with no
        // roles. Roles are only touched when the form actually submits them, so
        // a save that isn't about roles can never wipe them.
        if ($request->has('roles')) {
            \DB::table('model_has_roles')
                ->where('model_id', $user->id)
                ->where('model_type', User::class)
                ->delete();

            foreach ($roles as $roleName) {
                $roleId = \DB::table('roles')->where('name', $roleName)->value('id');
                if ($roleId) {
                    \DB::table('model_has_roles')->insert([
                        'role_id' => $roleId,
                        'model_type' => User::class,
                        'model_id' => $user->id,
                    ]);
                }
            }

            $user->clearRolesCache();
        }

        $user->clearPermissionCache();

        $user->parents()->sync($parents);

        return redirect()->route('admin.users.index')->with('status', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot delete yourself.']);
        }

        $actorIsSuperAdmin = auth()->user()->hasRole('super admin');
        $targetIsSuperAdmin = $user->hasRole('super admin');

        if ($targetIsSuperAdmin && ! $actorIsSuperAdmin) {
            return back()->withErrors(['error' => 'Only a Super Admin can delete another Super Admin account.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'User deleted successfully.');
    }
}
