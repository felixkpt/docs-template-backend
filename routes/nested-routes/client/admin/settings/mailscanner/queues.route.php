<?php
use App\Http\Controllers\Admin\Settings\Configs\QueuesController;
use Illuminate\Support\Facades\Route;

$controller = QueuesController::class;
Route::get('/',[$controller,'index']);
Route::post('/',[$controller,'storeQueue']);
Route::get('/list',[$controller,'listQueues']);
Route::get('/queue_email',[$controller,'getQueueMail']);
Route::delete('/delete/{queue}',[$controller,'destroyQueue']);
