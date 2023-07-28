<?php

namespace App\Http\Controllers\Admin\Settings\RolePermissions\Permissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function getUserPermissions(string $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        // Get JSON from storage
        $filePath = ('system/roles/' . Str::slug($role->name) . '.json');
        if (!Storage::exists($filePath)) {
            return response()->json(['error' => 'Role ' . $role->name . ' permissions file not found'], 404);
        }

        $jsonContent = file_get_contents(Storage::path($filePath));

        return response()->json(['role' => $role, 'data' => json_decode($jsonContent)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
