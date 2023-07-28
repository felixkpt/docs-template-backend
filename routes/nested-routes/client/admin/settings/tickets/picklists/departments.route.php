<?php

use App\Http\Controllers\Admin\Settings\Tickets\Picklists\DepartmentsController;
use Illuminate\Support\Facades\Route;

$controller = DepartmentsController::class;

Route::get('/',[$controller,'index']);
Route::post('/',[$controller,'storeDepartment']);
Route::get('/list',[$controller,'listDepartments']);
Route::get('/view/{department}',[$controller,'viewDepartment']);
Route::get('/list-users/{department}',[$controller,'listDepartmentUsers']);
Route::get('/get-users',[$controller,'getUsers']);
Route::post('/add-users',[$controller,'addDepartmentUser']);
Route::delete('/delete/{department}',[$controller,'destroyDepartment']);
Route::post('/activate/{id}',[$controller,'activate']);
Route::post('/deactivate/{id}',[$controller,'deactivate']);
Route::post('/user/remove/{id}',[$controller,'removeUser']);

