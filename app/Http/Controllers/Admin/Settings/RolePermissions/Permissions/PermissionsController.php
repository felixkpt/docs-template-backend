<?php

namespace App\Http\Controllers\Admin\Settings\RolePermissions\Permissions;

use App\Http\Controllers\Controller;
use App\Models\Permission as ModelsPermission;
use App\Repositories\SearchRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsController extends Controller
{
    public function index()
    {

        $permissions = Permission::whereNull('uri');

        if (request()->all == '1')
            return response(['results' => $permissions->get()]);

        $permissions = SearchRepo::of($permissions, ['name'], ['name', 'id'], ['name', 'guard_name'])
            ->addColumn('action', function ($permission) {
                return '
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="icon icon-list2 font-20"></i>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item prepare-view" href="/admin/settings/role-permissions/permission/' . $permission->id . '">View</a></li>
                <li><a class="dropdown-item prepare-edit" data-id="' . $permission->id . '" href="/admin/settings/role-permissions/permissions/' . $permission->id . '">Edit</a></li>
                <li><a class="dropdown-item prepare-status-update" data-id="' . $permission->id . '" href="/admin/settings/role-permissions/permissions/' . $permission->id . '/status-update">' . ($permission->status == 1 ? 'Deactivate' : 'Activate') . '</a></li>
            </ul>
        </div>
        ';
            })->paginate();

        return response(['results' => $permissions]);
    }

    public function store(Request $request)
    {

        $data = $request->all();

        $validateUser = Validator::make(
            $data,
            [
                'name' => 'required|unique:roles,name,' . $request->id . ',id',
                'description' => 'nullable',
                'guard_name' => 'required'
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $action = 'created';
        if ($request->id)
            $action = 'updated';

        $res = Permission::updateOrCreate(['id' => $request->id], $data);
        return response(['type' => 'success', 'message' => 'Permission ' . $action . ' successfully', 'results' => $res]);
    }

    function update(Request $request, $id)
    {
        $request->merge(['id' => $id]);
        return $this->store($request);
    }


    public function show($id)
    {
        $role = Permission::findOrFail($id);
        return response()->json([
            'status' => true,
            'results' => $role,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    function getRolePermissions($id)
    {


        if ($id === 'all') {
            $permissions = Permission::whereNotNull('uri');
        } else {
            $permission = Role::findOrFail($id);
            $permissions = $permission->permissions();
        }

        $permissions = $permissions->get();

        if (request()->uri)
            $permissions = $permissions->pluck('uri');

        return response(['results' => $permissions]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
