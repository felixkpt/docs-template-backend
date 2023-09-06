<?php

namespace App\Http\Controllers\Admin\Settings\RolePermissions\Permissions\Detail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionDetailController extends Controller
{
   
    public function show($id)
    {
        $role = Role::findOrFail($id);
        return response()->json([
            'status' => true,
            'results' => $role,
        ]);
    }

    function update(Request $request, $id)
    {
        $request->merge(['id' => $id]);
        return $this->store($request);
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
