<?php

use App\Http\Controllers\Admin\Settings\Sales\Picklists\DeliveryLocationController;
use Illuminate\Support\Facades\Route;
$controller = DeliveryLocationController::class;
Route::get('/', [$controller,'index']);
Route::post('/', [$controller,'storeDeliveryLocation']);
Route::get('/list', [$controller,'listDeliveryLocations']);
Route::post('/activate/{id}', [$controller, 'activate']);
Route::post('/deactivate/{id}', [$controller, 'deactivate']);
