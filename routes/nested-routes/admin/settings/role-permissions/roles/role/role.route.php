<?php

use App\Http\Controllers\Admin\Settings\RolePermissions\Roles\Role\RoleController;
use Illuminate\Support\Facades\Route;

$controller = RoleController::class;

Route::any('/{id}/save-permissions', [$controller, 'storeRolePermissions'])->name('Save Role Permissions')->hidden();
Route::any('/{id}/save-menu', [$controller, 'storeRoleMenu'])->name('Save Role Menu')->hidden();
Route::get('/{id}/get-role-menu', [$controller, 'getRoleMenu'])->hidden();
Route::get('/{id}/get-user-route-permissions', [$controller, 'getUserRoutePermissions'])->hidden();

Route::post('/{id}/add-user', [$controller, 'addUser'])->hidden();

Route::get('/{id}', [$controller, 'show'])->hidden();
Route::put('/{id}', [$controller, 'update'])->hidden();
