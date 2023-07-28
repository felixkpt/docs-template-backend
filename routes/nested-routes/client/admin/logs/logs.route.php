<?php
use App\Http\Controllers\Admin\Logs\UserAccessLogController;
use App\Http\Controllers\Admin\Logs\ExportImportLogsController;
use App\Http\Controllers\Admin\Logs\FailedLoginController;
use Illuminate\Support\Facades\Route;

$controller =UserAccessLogController::class;
Route::get('useraccesslogs',[$controller,'index']);
Route::get('useraccesslogs/list',[$controller,'listUsers']);
Route::get('useraccesslogs/export',[$controller,'exportUserAccessLogs']);

$exportImportLog =ExportImportLogsController::class;
Route::get('exportimportlogs',[$exportImportLog,'index']);
Route::get('exportimportlogs/store',[$exportImportLog,'storeExportImportLog']);
Route::get('exportimportlogs/list',[$exportImportLog,'listExportImportLogs']);

$failedlogin =FailedLoginController::class;
Route::get('failedlogin',[$failedlogin,'index']);
Route::get('failedlogin/list',[$failedlogin,'listFailedLoginAttempts']);
