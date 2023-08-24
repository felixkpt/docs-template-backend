<?php

use App\Http\Controllers\Admin\Settings\Users\User\UserController;
use Illuminate\Support\Facades\Route;

$controller = UserController::class;
Route::get('/{user}', [$controller, 'show'])->name('users.user.show')->icon('d');
Route::get('/{user}/edit', [$controller, 'edit'])->name('users.user.edit')->icon('d');
Route::put('/{user}', [$controller, 'update'])->name('users.user.update')->icon('e');
Route::delete('/{user}', [$controller, 'destroy'])->name('users.destroy')->icon('f');

Route::post('update', [$controller, 'userProfile']);
Route::get('token/{id}', [$controller, 'resendToken']);
Route::patch('profile-update', [$controller, 'profileUpdate']);
Route::patch('update-self-password', [$controller, 'updateSelfPassword'])->hidden(true);
Route::patch('update-others-password', [$controller, 'updateOthersPassword'])->hidden(true);
Route::post('unlock/{user_id}', [$controller, 'unlockUser']);
Route::post('activate/{id}', [$controller, 'activate']);
Route::post('deactivate/{id}', [$controller, 'deactivate']);
Route::post('login/{id}', [$controller, 'loginUser']);
Route::get('activity-log',[$controller, 'userActivityLog']);
