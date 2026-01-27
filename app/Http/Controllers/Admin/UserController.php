<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withTrashed()
            ->with('roles')
            ->latest()
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::where('guard_name', 'web')->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|min:2|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', 'min:8', Rules\Password::defaults()],
            'role'     => 'required|exists:roles,name',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'     => trim($validated['name']),
                'email'    => strtolower(trim($validated['email'])),
                'password' => Hash::make($validated['password']),
            ]);

            $user->assignRole($validated['role']);
        });

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

    public function show($id)
    {
        $user = User::withTrashed()
            ->with('roles')
            ->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user  = User::withTrashed()->findOrFail($id);
        $roles = Role::where('guard_name', 'web')->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'name'  => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role'  => 'required|exists:roles,name',
        ]);

        DB::transaction(function () use ($request, $validated, $user) {

            $user->update([
                'name'  => trim($validated['name']),
                'email' => strtolower(trim($validated['email'])),
            ]);

            if ($request->filled('password')) {
                $request->validate([
                    'password' => ['confirmed', 'min:8', Rules\Password::defaults()],
                ]);

                $user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            // ❗ Self role protection
            if ($user->id !== Auth::id()) {
                $user->syncRoles([$validated['role']]);
            }
        });

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete yourself');
        }

        $user->update(['deleted_by' => Auth::id()]);
        $user->delete();

        return back()->with('success', 'User moved to trash');
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        $user->restore();
        $user->update(['deleted_by' => null]);

        return back()->with('success', 'User restored successfully');
    }

    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete yourself');
        }

        // clean roles
        $user->roles()->detach();
        $user->forceDelete();

        return back()->with('success', 'User permanently deleted');
    }
}
