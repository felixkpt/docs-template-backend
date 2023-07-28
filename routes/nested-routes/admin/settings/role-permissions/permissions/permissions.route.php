<?php

use App\Http\Controllers\Admin\Settings\RolePermissions\Permissions\PermissionsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PermissionsController::class, 'index'])->hidden();
Route::post('/', [PermissionsController::class, 'store'])->hidden();
Route::get('/user/{id}', [PermissionsController::class, 'getUserPermissions'])->hidden();
