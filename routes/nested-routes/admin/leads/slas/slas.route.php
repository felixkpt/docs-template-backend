<?php

use App\Http\Controllers\Admin\Leads\Slas\SaleSlaController;
use Illuminate\Support\Facades\Route;

$controller = SaleSlaController::class;
Route::get('/',[$controller,'index']);
Route::get('/create',[$controller,'create']);
Route::get('/edit/{id}',[$controller,'edit']);
Route::post('/create',[$controller,'storeSaleSla']);
Route::get('/list',[$controller,'listSaleSlas']);
Route::delete('/delete/{salesla}',[$controller,'destroySaleSla']);
