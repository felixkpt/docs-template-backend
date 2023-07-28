<?php

use App\Http\Controllers\Admin\Settings\Debts\Picklists\PaymentModeController;
use Illuminate\Support\Facades\Route;

$controller = PaymentModeController::class;

Route::get('/',[$controller,'index']);
Route::post('/',[$controller,'storePaymentMode']);
Route::get('list',[$controller,'listPaymentModes']);
Route::delete('/delete/{paymentmode}',[$controller,'destroyPaymentMode']);
Route::post('/activate/{id}', [$controller, 'activate']);
Route::post('/deactivate/{id}', [$controller, 'deactivate']);
