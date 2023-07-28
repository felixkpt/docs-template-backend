<?php

use App\Http\Controllers\Admin\Permissiongroups\PermissionGroupsController;
use Illuminate\Support\Facades\Route;

$controller = PermissionGroupsController::class;

Route::get('/', [$controller, 'index']);
Route::post('/', [$controller, 'storePermissionGroup']);
Route::get('/permissions/{id}', [$controller, 'getPermissionGroupPermissions']);
Route::get('/view/{id}', [$controller, 'viewPermissionGroup']);
Route::post('/permissions/{id}', [$controller, 'updatePermissions']);
Route::post('/user', [$controller, 'addUser']);
Route::post('/user/remove/{id}', [$controller, 'removeUser']);
Route::get('/list', [$controller, 'listPermissionGroups']);
Route::get('/list/users', [$controller, 'listUsers']);
Route::get('/get/users', [$controller, 'getUsers']);
