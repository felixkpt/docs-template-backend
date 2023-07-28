<?php

use App\Http\Controllers\Admin\Sales\Products\ProductsController;
use Illuminate\Support\Facades\Route;


$controller = ProductsController::class;
Route::get('/', [$controller, 'index']);
Route::post('/',[$controller, 'storeProduct']);
Route::get('list', [$controller, 'listProducts']);
Route::get('/search', [$controller, 'searchProducts']);
Route::get('/delete{products}', [$controller, 'deleteProducts']);
