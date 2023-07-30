<?php

use App\Http\Controllers\Admin\Settings\RolePermissions\Permissions\PermissionsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PermissionsController::class, 'index']);
Route::post('/', [PermissionsController::class, 'store'])->hidden();
Route::get('/role/{id}', [PermissionsController::class, 'getRolePermissions'])->hidden();
Route::get('/user/{id}', [PermissionsController::class, 'getUserPermissions'])->hidden();
