<?php

use App\Http\Controllers\Admin\Settings\RolePermissions\Permissions\RoutesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RoutesController::class, 'index'])->name('Routes List')->hidden();
Route::post('/', [RoutesController::class, 'store'])->name('Store Route')->hidden();
