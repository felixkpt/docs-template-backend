<?php

use App\Http\Controllers\Admin\Customers\Detail\CustomerDetailController;
use Illuminate\Support\Facades\Route;

$controller = CustomerDetailController::class;

Route::get('/{customer}',[$controller,'index']);
