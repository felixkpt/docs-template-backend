<?php

use App\Http\Controllers\Admin\Settings\Tickets\Picklists\MasterDepartmentController;
use Illuminate\Support\Facades\Route;

$controller = MasterDepartmentController::class;
Route::get('/',[$controller,'index']);
Route::post('/',[$controller,'storeMasterDepartment']);
Route::get('/list',[$controller,'listMasterDepartments']);
Route::delete('/delete/{masterdepartment}',[$controller,'destroyMasterDepartment']);
Route::get('/view/{department}',[$controller,'viewDepartment']);
Route::get('/list-departments/{department}',[$controller,'listDepartments']);
Route::post('/activate/{id}',[$controller,'activate']);
Route::post('/deactivate/{id}',[$controller,'deactivate']);

Route::get('/search',[$controller,'searchDepartment']);
Route::post('/add-department',[$controller,'storeMapping']);
Route::post('/remove-department/{id}',[$controller,'deleteMapping']);
