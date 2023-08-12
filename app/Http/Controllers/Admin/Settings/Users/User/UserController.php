<?php

namespace App\Http\Controllers\Admin\Settings\Users\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\SearchRepo;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{


    public function show($id)
    {
        $user = User::with(['roles', 'direct_permissions'])->where(['users.id' => $id]);

        return response(['results' => SearchRepo::of($user, [], [], ['name', 'email', 'roles_multilist', 'direct_permissions_multilist', 'issue_source_list', 'disposition_list'])->first()]);
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);

        return response(['status' => true, 'results' => SearchRepo::of($user, [], [], ['name', 'email'])]);
    }

    public function update(Request $request, User $user)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'roles_list' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    if (count(array_filter($value, fn ($role) => $role !== null)) === 0) {
                        $fail('The roles field must contain at least one role.');
                    }
                },
            ],
            'direct_permissions_list' => 'nullable|array',
        ]);

        $request->merge(['roles' => request()->roles_list]);
        $request->merge(['permissions' => request()->direct_permissions_list]);


        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password') ? bcrypt($request->input('password')) : $user->password,
        ]);

        if ($request->roles) {

            $roles = Role::whereIn('id', $request->input('roles'))->get();
            $user->syncRoles($roles);
        }

        if ($request->permissions) {

            $permissions = Permission::whereIn('id', $request->input('permissions'))->get();
            $user->syncPermissions($permissions);
        }

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
