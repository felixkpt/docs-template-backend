<?php

use App\Http\Controllers\Admin\Users\UsersController;
use Illuminate\Support\Facades\Route;

$controller = UsersController::class;

Route::get('/', [$controller, 'index']);
Route::get('list', [$controller, 'listUsers']);
Route::get('/user/{id}', [$controller, 'viewUser']);
Route::get('/search', [$controller, 'searchUsers']);
Route::get('/emails', [$controller, 'searchEmails']);
Route::get('/emailsSearch', [$controller, 'searchUserEmails']);

Route::post('/user/update', [$controller, 'userProfile']);
Route::get('/user/token/{id}', [$controller, 'resendToken']);
Route::post('/', [$controller, 'saveUser']);
Route::post('/user/update-password', [$controller, 'updatePassword']);
Route::post('/user/unlock/{user_id}', [$controller, 'unlockUser']);
Route::post('/user/activate/{id}', [$controller, 'activate']);
Route::post('/user/deactivate/{id}', [$controller, 'deactivate']);
Route::delete('/delete/{user}', [$controller, 'destroyUser']);
Route::get('/user/login/{id}', [$controller, 'loginUser']);
Route::get('/failed-logins', [$controller, 'listAttemptedLogins']);
Route::get('activity-based-usage', [$controller, 'activityBasedUsage']);
Route::get('inactivity-based-usage',[$controller,'inactivityBasedUsage']);
Route::get('/export', [$controller, 'exportUsers']);
Route::get('user-activity-log',[$controller,'userActivityLog']);
Route::get('user-activity-log/list',[$controller,'listUserActivityLogs']);
Route::get('/user/activity-log',[$controller, 'userActivityLog']);
