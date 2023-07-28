<?php
use App\Http\Controllers\Admin\Sla\SlaLogsController;
use Illuminate\Support\Facades\Route;

$controller = SlaLogsController::class;

Route::get('/',[$controller,'index']);
Route::get('/list',[$controller,'listSlaLogs']);
Route::get('/export',[$controller,'exportSlaLogs']);
