<?php

namespace App\Http\Controllers\Admin\Settings\RolePermissions\Roles\Role;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{

    protected $leftTrim = 'api';
    protected $permissions = [];
    protected $folder_icons = [];
    protected $hidden_folders = [];

    function update(Request $request, $id)
    {
        $request->merge(['id' => $id]);
        return $this->store($request);
    }

    public function show($id)
    {
        $role = Role::findOrFail($id);
        return response()->json([
            'status' => true,
            'results' => $role,
        ]);
    }

    function storeRolePermissions(Request $request, $id)
    {

        $current_folder = $request->current_folder;
        $all_folders = $request->all_folders;

        $role = Role::with(['permissions' => function ($q) use ($current_folder) {
            $q->where('uri', 'not like', $current_folder[0]['folder'] . '%');
        }])->find($id);

        if (!$role) return response(['message' => 'Role not found', 'status' => false,], 404);

        $guard_name = $role->guard_name;
        $permissions = [];
        foreach ($current_folder as $nestedRoute) {
            $permissions = array_merge($permissions, $this->extractAndSavePermissions($nestedRoute, $permissions, $guard_name));
        }


        // update positions only
        foreach ($all_folders as $nestedRoute) {
            Permission::updateOrCreate(
                [
                    'name' => $nestedRoute['folder']
                ],
                [
                    'position' => $nestedRoute['position'],
                ]
            );
            Log::info('Updating folder position:', ['folder' => $nestedRoute['folder'], 'position' => $nestedRoute['position']]);
        }

        $existing = Role::find($role->id)->getAllPermissions()->pluck('id');

        $existing = $role->permissions->pluck('id');

        try {
            // Sync role with permissions
            $role->syncPermissions([...$existing, ...$permissions]);

            $this->saveJson($role, $all_folders);

            return response([
                'message' => 'Persssions saved!',
            ]);
        } catch (Exception $e) {

            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }

    function extractAndSavePermissions($nestedRoute, $permissions, $guard_name)
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

        $permissions[] = Permission::updateOrCreate(
            ['name' => $slug],
            [
                'name' => $slug,
                'uri' => $uri,
                'title' => $title,
                'icon' => $icon,
                'hidden' => $hidden,
                'position' => $position,
                'guard_name' => $guard_name,
                'user_id' => auth()->id() ?? 1
            ]
        )->id ?? 0;

        if (count($routes) > 0) {
            $permissions = array_merge($permissions, $this->saveRoutesASPermissions($routes, $guard_name));
        }

        if (count($children) > 0) {

            foreach ($children as $nestedRoute) {
                $permissions = array_merge($permissions, $this->extractAndSavePermissions($nestedRoute, $permissions, $guard_name));
            }
        }

        return $permissions;
    }

    /**
     * Update the specified resource in storage.
     */
    public function getRoleMenu(string $id)
    {
        sleep(1);
        // a user can have more than 1 roles
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        // Get JSON from storage
        $filePath = '/system/roles/' . Str::slug($role->name) . '_menu.json';

        if (!Storage::exists($filePath)) {
            return response()->json(['message' => 'Role ' . $role->name . ' permissions file not found'], 404);
        }

        $jsonContent = file_get_contents(Storage::path($filePath));

        // save user's default_role_id
        $user = User::find(auth()->id());
        $user->default_role_id = $id;
        $user->save();

        return response()->json(['results' => ['roles' => $role, 'menu' => json_decode($jsonContent)]]);
    }

    function saveRoutesASPermissions($routes, $guard_name)
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
                    'uri' => $uri,
                    'title' => $title,
                    'icon' => $icon,
                    'guard_name' => $guard_name,
                    'user_id' => auth()->id() ?? 1
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

    function addUser()
    {
        request()->validate([
            'role_id' => 'required|exists:roles,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $role = Role::find(request()->role_id);

        $user = User::find(request()->user_id);
        $user->assignRole($role);

        return response(['message' => "{$user->name} added to role {$role->name}"]);
    }
}
