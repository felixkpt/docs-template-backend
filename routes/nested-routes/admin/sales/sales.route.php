<?php

use App\Http\Controllers\Admin\Sales\SalesController;
use Illuminate\Support\Facades\Route;

$controller = SalesController::class;

Route::get('/', [$controller, 'index']);
Route::get('list', [$controller, 'listSales']);
Route::any('new/{search_term?}', [$controller, 'newSale']);
Route::get('create/{search_term?}', [$controller, 'createSale']);
Route::post('/', [$controller, 'storeSale']);
Route::get('search', [$controller, 'search']);
Route::post('add-to-cart', [$controller, 'addToCart']);
Route::get('add-to-cart', [$controller, 'addToCart']);
Route::get('check-cart', [$controller, 'checkCartItems']);
Route::get('checkout', [$controller, 'saleCheckoutView']);
Route::get('checkout-form/{id}', [$controller, 'checkoutForm']);
Route::post('check-cart/clear/{id?}', [$controller, 'clearCartItems']);
Route::get('today', [$controller, 'todaySalesView']);
