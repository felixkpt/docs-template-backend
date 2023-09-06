<?php

use App\Http\Controllers\Admin\DebtCollectionLists\DebtCollectionList\DebtCollectionListController;

use Illuminate\Support\Facades\Route;



Route::get('/view/{id}',[DebtCollectionListController::class, 'index']);
Route::get('/list/{id}', [DebtCollectionListController::class, 'listDebtCollectionListRecords']);
Route::post('/stats/{id}', [DebtCollectionListController::class, 'getDebtCollectionListStats']);
Route::get('/list/dailed/list/{id}',[DebtCollectionListController::class,'listDialedRecords']);




