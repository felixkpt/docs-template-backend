<?php

use App\Http\Controllers\Admin\Settings\Sales\SaleController;
use Illuminate\Support\Facades\Route;
$controller = SaleController::class;
Route::get('/', [$controller,'index']);