<?php
// routes/users/users.php

use App\Http\Controllers\Admin\Settings\Users\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'index'])->name('users.index')->icon('mdi:leads');
Route::get('/create', [UserController::class, 'create'])->name('users.create')->icon('prime:bookmark')->hidden(true);
Route::post('/', [UserController::class, 'store'])->name('users.store')->icon('c');
Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->icon('d');
Route::put('/{user}', [UserController::class, 'update'])->name('users.update')->icon('e');
Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy')->icon('f');
