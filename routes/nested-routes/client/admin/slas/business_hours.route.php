<?php
use App\Http\Controllers\Admin\Sla\BusinessHoursController;
use Illuminate\Support\Facades\Route;

$controller = BusinessHoursController::class;
Route::get('/',[$controller,'index']);
Route::post('/',[$controller,'storeBusinessHour']);
Route::get('/edit/{id}',[$controller,'editSla']);
Route::get('/list',[$controller,'listBusinessHours']);
