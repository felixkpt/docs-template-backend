<?php

use App\Http\Controllers\Admin\Settings\RolePermissions\Permissions\Detail\PermissionDetailController;
use Illuminate\Support\Facades\Route;

$controller = PermissionDetailController::class;
Route::put('/{id}', [$controller, 'update'])->hidden();
