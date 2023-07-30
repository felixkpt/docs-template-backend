<?php

namespace App\Http\Controllers\Admin\Settings\RolePermissions\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Spatie\Permission\Models\Role;
use App\Repositories\SearchRepo;
use App\Services\NestedRoutes\GetNestedRoutes;
use Illuminate\Http\Request;
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

        $roles = SearchRepo::of($roles, ['name'], ['name', 'id'], ['name', 'guard_name'])
            ->addColumn('action', function ($role) {
                return '
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="icon icon-list2 font-20"></i>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item navigate" href="/admin/settings/role-permissions/roles/' . $role->id . '">View</a></li>
                <li><a class="dropdown-item prepare-edit" data-id="' . $role->id . '" href="/admin/settings/role-permissions/roles/' . $role->id . '">Edit</a></li>
                <li><a class="dropdown-item prepare-status-update" data-id="' . $role->id . '" href="/admin/settings/role-permissions/roles/' . $role->id . '/status-update">' . ($role->status == 1 ? 'Deactivate' : 'Activate') . '</a></li>
            </ul>
        </div>
        ';
            })->paginate();

        return response(['data' => $roles]);
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
        return response(['type' => 'success', 'message' => 'Role ' . $action . ' successfully', 'data' => $res]);
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
            'data' => $role,
        ]);
    }

    function storePermissions(Request $request, $id)
    {

        $role = Role::find($id);
        if (!$role) return response(['message' => 'Role not found', 'status' => false,], 404);

        $validator = Validator::make(
            $request->all(),
            [
                'permissions' => 'required|array',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 401);
        }

        $permissions = $request->permissions ?? [];

        $databasePermissions = [];
        $jsonPermissions = [];
        foreach ($permissions as $permission) {
            $parts = explode(',', $permission);

            $uri = $parts[0];
            $title = Str::slug($parts[1], '_');
            $slug = Str::slug(Str::replace('/', ' ', Str::before($uri, '@')), '.');
            if (!Str::endsWith($slug, $title)) $slug .= '.' . $title;

            $databasePermissions[] = Permission::updateOrCreate(['name' => $slug], ['name' => $slug, 'uri' => $uri, 'guard_name' => 'api', 'user_id' => auth()->id() ?? 1]);
            $jsonPermissions[] = $uri;
        }

        // Sync role with permissions
        $role->syncPermissions($databasePermissions);

        // generate role json
        $this->generateJson($role, $jsonPermissions, $request);

        return response([
            'status' => 'success',
            'message' => 'Persssions saved!',
            'data' => Permission::whereNotNull('uri')->get()
        ]);
    }

    function generateJson($role = null, $permissions = null, $request = null)
    {

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
