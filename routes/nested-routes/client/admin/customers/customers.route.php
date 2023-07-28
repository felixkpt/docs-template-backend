<?php
use App\Http\Controllers\Admin\Customers\CustomersController;
use Illuminate\Support\Facades\Route;

$controller = CustomersController::class;
Route::get('/',[$controller,'index']);
Route::post('/',[$controller,'storeCustomer']);
Route::get('/list',[$controller,'listCustomers']);
Route::get('/new-customer-modal',[$controller,'newCustomerModalForm']);
Route::get('/edit-customer-modal',[$controller,'editCustomerModalForm']);
Route::delete('/delete/{customer}',[$controller,'destroyCustomer']);
Route::get('emails',[$controller,'searchEmails']);

