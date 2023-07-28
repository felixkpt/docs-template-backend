<?php
use App\Http\Controllers\Admin\Sales\Sale\Saleupdate\SaleUpdateController;
use Illuminate\Support\Facades\Route;

$controller = SaleUpdateController::class;

Route::get('show/{sale}', [$controller,'show']);
Route::post('/{sale}',[$controller,'store']);
