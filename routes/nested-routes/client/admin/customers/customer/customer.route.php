<?php

use App\Http\Controllers\Admin\Customers\Customer\CustomerController;
use Illuminate\Support\Facades\Route;

$controller = CustomerController::class;

Route::get('/{customer}',[$controller,'index']);
