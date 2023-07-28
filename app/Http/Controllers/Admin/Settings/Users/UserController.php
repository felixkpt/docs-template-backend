<?php

namespace App\Http\Controllers\Admin\Settings\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        return response(['data' => $users, 'status' => true, 'message' => 'Users list']);
    }

    public function create()
    {
        $roles = Role::all();

        return response(['status' => true, 'data' => ['user' => null, 'roles' => $roles]]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8',
            'roles' => 'nullable|array',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        if ($request->roles) {
            $roles = Role::whereIn('id', $request->input('role_id'))->get();
            foreach ($roles as $r)
                $user->syncRoles([$r->name]);
        }

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();

        return response(['status' => true, 'data' => ['user' => $user, 'roles' => $roles]]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'roles' => 'required|array',
        ]);

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password') ? bcrypt($request->input('password')) : $user->password,
        ]);

        if ($request->roles) {
            $roles = Role::whereIn('id', $request->input('roles'))->get();
            foreach ($roles as $r)
                $user->assignRole($r);

        }

        // Additional logic if needed

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        // Additional logic if needed

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
