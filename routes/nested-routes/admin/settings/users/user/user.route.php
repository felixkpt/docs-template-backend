<?php
// routes/users/users.php

use App\Http\Controllers\Admin\Settings\Users\User\UserController;
use Illuminate\Support\Facades\Route;

$controller = UserController::class;
Route::get('/{user}', [$controller, 'show'])->name('users.user.show')->icon('d');
Route::get('/{user}/edit', [$controller, 'edit'])->name('users.user.edit')->icon('d');
Route::put('/{user}', [$controller, 'update'])->name('users.user.update')->icon('e');
Route::delete('/{user}', [$controller, 'destroy'])->name('users.destroy')->icon('f');
