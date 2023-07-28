<?php

use App\Http\Controllers\Admin\Settings\Tickets\Picklists\DispositionsController;
use Illuminate\Support\Facades\Route;

$controller = DispositionsController::class;

Route::get('/',[$controller,'index']);
Route::post('/',[$controller,'storeDisposition']);
Route::get('/list',[$controller,'listDispositions']);
Route::get('/search',[$controller,'searchDispositions']);
Route::post('/activate/{id}',[$controller,'activate']);
Route::post('/deactivate/{id}',[$controller,'deactivate']);
