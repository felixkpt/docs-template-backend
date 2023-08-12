<?php

namespace App\Http\Controllers\Admin\Settings\RolePermissions\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Spatie\Permission\Models\Role;
use App\Repositories\SearchRepo;
use App\Services\NestedRoutes\GetNestedRoutes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{

    protected $leftTrim = 'api';
    protected $permissions = [];
    protected $folder_icons = [];
    protected $hidden_folders = [];

    public function index()
    {

        $roles = Role::query();

        if (request()->all == '1')
            return response(['results' => $roles->get()]);

        $roles = SearchRepo::of($roles, ['name'], ['name', 'id'], ['name', 'guard_name'])
            ->addColumn('action', function ($role) {
                return '
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="icon icon-list2 font-20"></i>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item navigate" href="/admin/settings/role-permissions/roles/' . $role->id . '">View</a></li>
                <li><a class="dropdown-item prepare-edit" data-id="' . $role->id . '" href="/admin/settings/role-permissions/roles/' . $role->id . '/edit">Edit</a></li>
                <li><a class="dropdown-item prepare-status-update" data-id="' . $role->id . '" href="/admin/settings/role-permissions/roles/' . $role->id . '/status-update">' . ($role->status == 1 ? 'Deactivate' : 'Activate') . '</a></li>
            </ul>
        </div>
        ';
            })->paginate();

        return response(['results' => $roles]);
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

        $res = Role::updateOrCreate(['id' => $request->id], $data);
        return response(['type' => 'success', 'message' => 'Role ' . $action . ' successfully', 'results' => $res]);
    }

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

    function storePermissions(Request $request, $id)
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

        $existing = Role::find($role->id)->getAllPermissions()->pluck('id');

        $existing = $role->permissions->pluck('id');

        // Sync role with permissions
        $role->syncPermissions([...$existing, ...$permissions]);

        $this->saveJson($role, $all_folders);

        return response([
            'status' => 'success',
            'message' => 'Persssions saved!',
            'results' => []
        ]);
    }

    function extractAndSavePermissions($nestedRoute, $permissions, $guard_name)
    {

        $folder = $nestedRoute['folder'];
        $title = $nestedRoute['title'];
        $icon = $nestedRoute['icon'];
        $hidden = $nestedRoute['hidden'];
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
    public function getRolePermissions(string $id)
    {
        // a user can have more than 1 roles
        $permissions = Role::find($id);
        if (!$permissions) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        // Get JSON from storage
        $filePath = '/system/roles/' . Str::slug($permissions->name) . '_menu.json';

        if (!Storage::exists($filePath)) {
            return response()->json(['message' => 'Role ' . $permissions->name . ' permissions file not found'], 404);
        }

        $jsonContent = file_get_contents(Storage::path($filePath));

        return response()->json(['results' => ['roles' => $permissions, 'menu' => json_decode($jsonContent)]]);
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

    function generateJson($role = null, $permissions = null, $request = null)
    {
        return $this->storePermissions(request(), 54);

        $this->permissions = $permissions;
        $this->folder_icons = $request->folder_icons;
        $this->hidden_folders = $request->hidden_folders;

        $allRoutes = (new GetNestedRoutes())->list();
        $filteredRoutes = $this->filterAllRoutes($allRoutes);

        $filePath = storage_path('/app/system/roles/' . Str::slug($role->name) . '_permissions.json');
        $jsonString = json_encode($filteredRoutes, JSON_PRETTY_PRINT);

        // Create the directory if it does not exist
        $filesystem = new Filesystem();
        $filesystem->makeDirectory(dirname($filePath), 0755, true, true);

        // Save the JSON data to the file
        $filesystem->put($filePath, $jsonString);
    }

    function filterAllRoutes($routes, $folder = '')
    {
        $filteredRoutes = [];

        foreach ($routes as $key => $item) {
            $currentFolder = trim($folder . '/' . $key, '/');

            if ($this->includeFolder($currentFolder) && !$this->isHiddenFolder($currentFolder)) {

                $item['icon'] = $this->attachIcon($currentFolder);

                if (isset($item['children'])) {
                    $newRoutes = null;
                    if (isset($item['children']['routes']) && count($item['children']['routes']) > 0) {
                        $newRoutes = $this->filterRoutes($item['children']['routes']);
                    }

                    $filteredChildRoutes = $this->filterAllRoutes($item['children'], $currentFolder);
                    $filteredChildRoutes['routes'] = $newRoutes ?? [];

                    if (!empty($filteredChildRoutes)) {
                        $item['children'] = $filteredChildRoutes;
                    } else {
                        unset($item['children']);
                    }
                }

                $filteredRoutes[$key] = $item;
            }
        }

        return $filteredRoutes;
    }

    function filterRoutes($routes)
    {
        return array_values(array_filter($routes, fn ($r) => in_array($r['uri_methods'], $this->permissions)));
    }

    function includeFolder($folder)
    {
        return in_array($folder, $this->permissions);
    }

    function isHiddenFolder($folder)
    {
        return in_array($folder, $this->hidden_folders);
    }

    function attachIcon($folder)
    {
        foreach ($this->folder_icons as $folder_icon) {

            if ($folder_icon[0] == $folder)
                return  $folder_icon[1];
        }

        return null;
    }

    public function destroy($permissiongroup_id)
    {
        $permissiongroup = Role::findOrFail($permissiongroup_id);
        if ($permissiongroup->is_default)
            return response(['type' => 'failure', 'message' => 'Default PermissionGroup cannot be deleted']);

        $permissiongroup->delete();
        return response(['type' => 'success', 'message' => 'PermissionGroup deleted successfully']);
    }
}
