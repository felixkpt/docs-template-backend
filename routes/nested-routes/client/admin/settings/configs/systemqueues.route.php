<?php
use App\Http\Controllers\Admin\Settings\Configs\SystemQueuesController;
use Illuminate\Support\Facades\Route;

$controller = SystemQueuesController::class;
Route::get('/',[$controller,'index']);
Route::post('/',[$controller,'storeSystemQueue']);
Route::get('/list',[$controller,'listSystemQueues']);
Route::delete('/delete/{systemqueue}',[$controller,'destroySystemQueue']);
Route::post('/activate/{id}',[$controller,'activate']);
Route::post('/deactivate/{id}',[$controller,'deactivate']);
Route::post('/user/remove/{id}',[$controller,'removeUser']);
Route::get('/list-users/{systemqueue}',[$controller,'listSystemQueueUsers']);
Route::get('/get-users',[$controller,'getUsers']);
Route::post('/add-users',[$controller,'addSystemQueueUser']);
Route::get('/view/{systemqueue}',[$controller,'viewSystemQueue']);
