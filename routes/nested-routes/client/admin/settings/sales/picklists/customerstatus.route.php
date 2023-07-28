<?php

use App\Http\Controllers\Admin\Settings\Sales\Picklists\CustomerStatusController;
use Illuminate\Support\Facades\Route;

$controller = CustomerStatusController::class;
Route::get('/', [$controller, 'index']);
Route::post('/', [$controller, 'storeCustomerStatus']);
Route::get('/list', [$controller, 'listCustomerStatuses']);
Route::post('/activate/{id}', [$controller, 'activate']);
Route::post('/deactivate/{id}', [$controller, 'deactivate']);
