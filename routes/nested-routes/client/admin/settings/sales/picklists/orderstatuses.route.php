<?php

use App\Http\Controllers\Admin\Settings\Sales\Picklists\OrderStatusesController;
use Illuminate\Support\Facades\Route;
$controller = OrderStatusesController::class;
Route::get('/', [$controller,'index']);
Route::post('/', [$controller,'storeOrderStatuses']);
Route::get('/list', [$controller,'listOrderStatuses']);
Route::post('/activate/{id}', [$controller, 'activate']);
Route::post('/deactivate/{id}', [$controller, 'deactivate']);
