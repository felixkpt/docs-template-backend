<?php


use App\Http\Controllers\Admin\Users\UsersController;
use Illuminate\Support\Facades\Route;

$controller = UsersController::class;


Route::get('/user/{id}',[$controller,'viewUser']);


Route::get('/',[$controller,'index']);
Route::get('list',[$controller,'listUsers']);
Route::post('/user/update',[$controller,'userProfile']);
Route::get('/user/token/{id}',[$controller,'resendToken']);
Route::post('/',[$controller,'saveUser']);
Route::post('/user/update-password',[$controller,'updatePassword']);
Route::delete('/delete/{user}',[$controller,'destroyUser']);
Route::get('/user/login/{id}',[$controller,'loginUser']);
Route::post('/user/update-api-token/{id}',[$controller,'updateApiToken']);

Route::get('/search',[$controller,'searchUsers']);
Route::get('/emails',[$controller,'searchEmails']);
