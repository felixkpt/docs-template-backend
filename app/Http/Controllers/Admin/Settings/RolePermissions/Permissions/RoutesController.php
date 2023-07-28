<?php

namespace App\Http\Controllers\Admin\Settings\RolePermissions\Permissions;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Services\NestedRoutes\GetNestedRoutes;
use Illuminate\Support\Str;

class RoutesController extends Controller
{
    public Permission $permission;

    public function index()
    {
        $prefix = 'admin';
        $gen = new GetNestedRoutes($prefix, '');
        $nestedRoutes = $gen->list($prefix);

        return response($nestedRoutes);
    }

    function store()
    {

        if (request()->checked)
            foreach (request()->checked as $uri) {
                $slug = Str::slug(Str::replace('/', ' ', Str::before($uri, '@')), '.');

                Permission::updateOrCreate(['name' => $slug], ['name' => $slug, 'uri' => $uri, 'guard_name' => 'api', 'user_id' => auth()->id()]);
            }

        return response([
            'status' => 'success',
            'message' => 'Persssions saved!',
            'data' => Permission::whereNotNull('uri')->get()
        ]);
    }
}
