<?php

use App\Http\Controllers\Admin\Settings\RolePermissions\Permissions\PermissionsController;
use Illuminate\Support\Facades\Route;

$controller = PermissionsController::class;
Route::get('/', [$controller, 'index']);
Route::post('/', [$controller, 'store'])->hidden();
Route::get('/get-role-permissions/{role_id}', [$controller, 'getRolePermissions'])->hidden();
