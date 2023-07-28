<?php

use App\Http\Controllers\Admin\Settings\RolePermissions\Roles\RolesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RolesController::class, 'index']);
Route::post('/', [RolesController::class, 'store'])->hidden();
Route::get('/generateJson', [RolesController::class, 'generateJson'])->hidden();
Route::get('/{id}', [RolesController::class, 'show'])->hidden();
Route::put('/{id}', [RolesController::class, 'update'])->hidden();
Route::post('/{id}/save-permissions', [RolesController::class, 'storePermissions'])->hidden();