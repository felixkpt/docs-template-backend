<?php

use App\Http\Controllers\Admin\Settings\Sales\Picklists\ProductCategoryController;
use Illuminate\Support\Facades\Route;
$controller = ProductCategoryController::class;
Route::get('/', [$controller,'index']);
Route::post('/', [$controller,'storeProduct']);
Route::get('/list', [$controller,'listProductCategory']);
Route::post('/activate/{id}', [$controller, 'activate']);
Route::post('/deactivate/{id}', [$controller, 'deactivate']);
