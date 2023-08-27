<?php

use App\Http\Controllers\Admin\Settings\RolePermissions\Permissions\PermissionsController;
use Illuminate\Support\Facades\Route;

$controller = PermissionsController::class;
Route::get('/', [$controller, 'index']);
Route::post('/', [$controller, 'store'])->hidden();
Route::put('/{id}', [$controller, 'update'])->hidden();
Route::get('/get-role-permissions/{id}', [$controller, 'getRolePermissions'])->hidden();
