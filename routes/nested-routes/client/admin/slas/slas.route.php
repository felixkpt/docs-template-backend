<?php
use App\Http\Controllers\Admin\Sla\SlaController;
use Illuminate\Support\Facades\Route;

$controller = SlaController::class;
Route::get('/',[$controller,'index']);
Route::post('/',[$controller,'storeSla']);
Route::get('/edit/{id}',[$controller,'editSla']);
Route::get('/list',[$controller,'listSlas']);
Route::get('/create',[$controller,'createSla']);
Route::delete('/delete/{sla}',[$controller,'destroySla']);
