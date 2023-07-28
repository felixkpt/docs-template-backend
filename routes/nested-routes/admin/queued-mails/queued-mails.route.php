<?php

use App\Http\Controllers\Admin\QueuedMails\QueuedMailsController;
use Illuminate\Support\Facades\Route;

$controller = QueuedMailsController::class;

Route::get('/',[$controller,'index']);
Route::get('/list',[$controller,'listQueuedMails']);
Route::get('/stats',[$controller,'getStats']);
Route::get('/get/any/{id}',[$controller,'getQueuedMail']);
Route::get('/export',[$controller,'exportQueuedMails']);
