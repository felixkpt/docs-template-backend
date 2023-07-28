<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Sales\Sale\Salesorder\SalesOrderController;

$controller = SalesOrderController::class;
Route::get('{id}', [$controller, 'index']);
Route::get('download/{id}', [$controller, 'download']);
Route::get('/mark-sent{saleorder}', [$controller, 'markSent']);
