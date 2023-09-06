<?php

namespace App\Http\Controllers\Admin\Settings\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\SearchRepo;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class UsersController extends Controller
{
    public function index()
    {

        $users = User::with(['roles'])->when(request()->role_id, function ($q) {
            if (request()->has('negate')) {
                $q->whereDoesntHave('roles', function ($q) {
                    $q->where('roles.id', request()->role_id);
                });
            } else {
                $q->whereHas('roles', function ($q) {
                    $q->where('roles.id', request()->role_id);
                });
            }
        });

        $users = SearchRepo::of($users, ['name', 'id'])
            ->addColumn('Roles', function ($user) {
                return implode(', ', $user->roles()->get()->pluck('name')->toArray());
            })
            ->addColumn('action', function ($user) {
                return '
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="icon icon-list2 font-20"></i>
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item autotable-navigate" href="/admin/users/detail/' . $user->id . '">View</a></li>
            '
                    .
                    (checkPermission('users', 'post') ?
                        '<li><a class="dropdown-item autotable-edit" data-id="' . $user->id . '" href="/admin/users/detail/' . $user->id . '/edit">Edit</a></li>'
                        :
                        '')
                    .
                    '<li><a class="dropdown-item autotable-status-update" data-id="' . $user->id . '" href="/admin/users/detail/' . $user->id . '/status-update">' . ($user->status == 1 ? 'Deactivate' : 'Activate') . '</a></li>
            <li><a class="dropdown-item autotable-delete" data-id="' . $user->id . '" href="/admin/users/detail/' . $user->id . '">Delete</a></li>
        </ul>
    </div>
    ';
            })->paginate();

        return response(['results' => $users, 'status' => true]);
    }

    public function create()
    {

        return response(['status' => true, 'results' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8',
            'roles' => 'required|array',
            'permissions' => 'nullable|array',
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

        if ($request->permissions) {
            $permissions = Permission::whereIn('id', $request->input('role_id'))->get();
            foreach ($roles as $r)
                $user->syncRoles([$r->name]);
        }

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);

        return response(['status' => true, 'results' => $user]);
    }

    public function update(Request $request, User $user)
    {

        $request->merge(['roles' => json_decode(request()->roles)]);

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
            $user->syncRoles($roles);
        }

        // Additional logic if needed

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
