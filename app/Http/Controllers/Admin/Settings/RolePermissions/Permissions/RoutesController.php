<?php

namespace App\Http\Controllers\Admin\Settings\RolePermissions\Permissions;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Services\NestedRoutes\GetNestedRoutes;

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
    
    function store() {
        
        dd($this->checkedRoutes);
        $re = $this->validate();

        return redirect()->to('/admin/settings/permissins/list-routes');
    }
}
