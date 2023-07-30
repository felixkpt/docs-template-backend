<?php

namespace App\Http\Controllers\Admin\Settings\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\SearchRepo;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles');
        $users = SearchRepo::of($users, ['name', 'id'])
            ->addColumn('action', function ($user) {
                return '
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="icon icon-list2 font-20"></i>
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item prepare-navigate" href="/admin/settings/users/user/' . $user->id . '">View</a></li>
            <li><a class="dropdown-item prepare-editt" data-id="' . $user->id . '" href="/admin/settings/users/user/' . $user->id . '/edit">Edit</a></li>
            <li><a class="dropdown-item prepare-status-update" data-id="' . $user->id . '" href="/admin/settings/users/user/' . $user->id . '/status-update">' . ($user->status == 1 ? 'Deactivate' : 'Activate') . '</a></li>
            <li><a class="dropdown-item prepare-delete" data-id="' . $user->id . '" href="/admin/settings/users/user/' . $user->id . '">Delete</a></li>
        </ul>
    </div>
    ';
            })->paginate();

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
