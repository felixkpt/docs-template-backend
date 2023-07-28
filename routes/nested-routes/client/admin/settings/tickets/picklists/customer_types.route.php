<?php
use App\Http\Controllers\Admin\Settings\Tickets\Picklists\CustomerTypeController;
use Illuminate\Support\Facades\Route;

$controller = CustomerTypeController::class;

Route::get('/',[$controller,'index']);
Route::post('/',[$controller,'storeCustomerType']);
Route::get('/list',[$controller,'listCustomerTypes']);
Route::delete('/delete/{customertype}',[$controller,'destroyCustomerType']);
