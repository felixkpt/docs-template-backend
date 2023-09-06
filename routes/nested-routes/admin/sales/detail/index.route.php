<?php
use App\Http\Controllers\Admin\Sales\Sale\SaleController;
use Illuminate\Support\Facades\Route;
    $controller = SaleController::class;
Route::get('/{id}', [$controller, 'index']);
Route::get('edit/{id}',[$controller,'edit']);
Route::get('items/{id}', [$controller, 'getItems']);
Route::get('edit-item/{sale_product_id}',[$controller,'editSaleProduct']);
Route::post('update-item',[$controller,'updateSaleProduct']);
Route::post('add-item/{sale_id}',[$controller,'addItem']);



                                                                                                                                                                                                                                                                                                                                                                                                    