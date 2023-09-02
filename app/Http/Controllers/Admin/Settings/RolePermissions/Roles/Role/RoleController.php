<?php

namespace App\Http\Controllers\Admin\Settings\RolePermissions\Roles\Role;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{

    protected $leftTrim = 'api';
    protected $permissions = [];
    protected $folder_icons = [];
    protected $hidden_folders = [];

    protected $checked_permissions = [];

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
     * Store role permissions for a specific role.
     *
     * @param  Request $request
     * @param  int     $id
     * @return Response
     */
    function storeRolePermissions(Request $request, $id)
    {
        $request->validate([
            'current_folder' => ['required', 'array'],
        ]);

        $start = Carbon::now();

        // Get the current folder from the request
        $current_folder = $request->current_folder;
        $parent_folder = $current_folder['folder'];
        $unchecked = $current_folder['unchecked'];

        // Find the role by ID along with its permissions, excluding those from the current folder
        $role = Role::find($id);

        // If the role doesn't exist, return a 404 response
        if (!$role) {
            return response(['message' => 'Role not found', 'status' => false], 404);
        }

        // Get the guard name of the role
        $guard_name = $role->guard_name;

        // Extract and save permissions for the current folder
        $this->extractAndSavePermissions($parent_folder, $current_folder, $guard_name);

        Log::info('checked_permissions', ['Folder' => $parent_folder, 'Perms' => $this->checked_permissions]);

        sleep(2);

        try {
            DB::beginTransaction();

            // Step 1: Delete old permissions that haven't been updated in the current request
            $untouched_permissions = Permission::where('parent_folder', $parent_folder)
                ->where('updated_at', '<', Carbon::createFromTimestamp($start->timestamp))
                ->whereNotIn('uri', $unchecked);

            $count = $untouched_permissions->count();

            // Delete outdated permissions
            $untouched_permissions->delete();

            // Find the role by ID along with its permissions, excluding those from the current folder
            $existing = Role::with(['permissions' => function ($q) use ($parent_folder) {
                $q->where('parent_folder', '!=', $parent_folder);
            }])->find($id)->permissions->pluck('id')->toArray() ?? [];


            // Step 3: Merge existing and current permissions
            $mergedPermissions = array_merge($existing, $this->checked_permissions);

            Log::critical('mergedPermissions count:', ['existing' => $existing]);

            // Step 4: Sync role with permissions
            $role->syncPermissions([$mergedPermissions]);
            DB::commit();

            return response([
                'message' => "Permissions have been successfully updated for {$parent_folder}. {$count} outdated route permissions have been removed.",
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }

    function storeRoleMenuAndCleanPermissions(Request $request, $id)
    {

        $menu = $request->menu;
        $saved_folders = $request->saved_folders;
        $all_folders = $request->all_folders;

        $request->validate([
            'menu' => ['required', 'array'],
            'saved_folders' => ['required', 'array'],
            'all_folders' => ['required', 'array'],
        ]);

        $role = Role::find($id);
        if (!$role) return response(['message' => 'Role not found', 'status' => false,], 404);

        // 1. Remove permissions for parent folders not in the list of saved folders (probably the current role does not need the folders anymore)
        $permissionsToRemove = $role->permissions()->whereNotIn('parent_folder', $saved_folders)->pluck('id')->toArray();
        Log::info('permissionsToRemove', ['permissionsToRemove' => $permissionsToRemove]);
        $role->permissions()->detach($permissionsToRemove);

        // 2. Delete permissions for parent folders not in all_folders (probably the folders were renamed or deleted)
        $permissionsToDelete = Permission::whereNotIn('parent_folder', $all_folders);
        $permissionsToDelete->delete();

        try {
            $this->saveJson($role, $menu);

            return response([
                'message' => 'Menu saved!',
            ]);
        } catch (Exception $e) {

            return response([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    function extractAndSavePermissions($parent_folder, $nestedRoute, $guard_name)
    {

        $folder = $nestedRoute['folder'];
        $title = $nestedRoute['title'];
        $icon = $nestedRoute['icon'];
        $hidden = $nestedRoute['hidden'];
        $position = $nestedRoute['position'] ?? 999999;
        $children = $nestedRoute['children'];
        $routes = $nestedRoute['routes'];

        $uri = $folder;
        $slug = Str::slug(Str::replace('/', ' ', $uri), '.');

        $this->checked_permissions[] = Permission::updateOrCreate(
            ['name' => $slug],
            [
                'name' => $slug,
                'uri' => $uri,
                'title' => $title,
                'icon' => $icon,
                'hidden' => $hidden,
                'parent_folder' => $parent_folder,
                'position' => $position,
                'guard_name' => $guard_name,
                'user_id' => auth()->id() ?? 1,
                'updated_at' => Carbon::now(),
            ]
        )->id ?? 0;

        if (count($routes) > 0) {
            array_push($this->checked_permissions, ...$this->saveRoutesASPermissions($parent_folder, $routes, $guard_name));
        }

        if (count($children) > 0) {

            foreach ($children as $nestedRoute) {
                $this->extractAndSavePermissions($parent_folder, $nestedRoute, $guard_name);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function getRoleMenu(string $id)
    {
        // a user can have more than 1 roles
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        // save user's default_role_id
        $user = User::find(auth()->id());
        $user->default_role_id = $id;
        $user->save();

        // Get JSON from storage
        $filePath = '/system/roles/' . Str::slug($role->name) . '_menu.json';

        if (!Storage::exists($filePath)) {
            return response()->json(['message' => 'Role ' . $role->name . ' permissions file not found'], 404);
        }

        $jsonContent = file_get_contents(Storage::path($filePath));

        return response()->json(['results' => ['roles' => $role, 'menu' => json_decode($jsonContent)]]);
    }

    function saveRoutesASPermissions($parent_folder, $routes, $guard_name)
    {
        $permissions = [];
        foreach ($routes as $route) {

            $uri = $route['uri'];
            $title = $route['title'];
            $icon = $route['icon'];
            $slug = Str::slug(Str::replace('/', ' ', $uri), '.');

            $permissions[] = Permission::updateOrCreate(
                ['name' => $slug],
                [
                    'name' => $slug,
                    'parent_folder' => $parent_folder,
                    'uri' => $uri,
                    'title' => $title,
                    'icon' => $icon,
                    'guard_name' => $guard_name,
                    'user_id' => auth()->id() ?? 1,
                    'updated_at' => Carbon::now(),
                ]
            )->id ?? 0;
        }

        return $permissions;
    }

    function saveJson($role, $json)
    {

        $filePath = storage_path('/app/system/roles/' . Str::slug($role->name) . '_menu.json');
        $jsonString = json_encode($json, JSON_PRETTY_PRINT);

        // Create the directory if it does not exist
        $filesystem = new Filesystem();
        $filesystem->makeDirectory(dirname($filePath), 0755, true, true);

        // Save the JSON data to the file
        $filesystem->put($filePath, $jsonString);
    }

    function getUserRoutePermissions($id)
    {
        $role = Role::findOrFail($id);
        $user = User::find(auth()->user()->id);

        if (!$user->hasRole($role)) return response(['message' => "User doesnt have the {$role->id} role."], 404);

        // Get all permissions associated with user's roles
        $route_permissions = $role->permissions->pluck('uri');

        return response(['results' => $route_permissions]);
    }

    function addUser($id)
    {

        $role = Role::find($id);
        if (!$role) return response(['message' => 'Role not found', 'status' => false,], 404);

        request()->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::find(request()->user_id);
        $user->assignRole($role);

        return response(['results' => $user, 'message' => "{$user->name} added to role {$role->name}"]);
    }
}
