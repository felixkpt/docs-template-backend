<?php

use App\Http\Controllers\Admin\Settings\RolePermissions\Permissions\RoutesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RoutesController::class, 'index'])->hidden();
Route::post('/', [RoutesController::class, 'store'])->hidden();
Route::get('/user/{id}', [RoutesController::class, 'getUserPermissions'])->hidden();