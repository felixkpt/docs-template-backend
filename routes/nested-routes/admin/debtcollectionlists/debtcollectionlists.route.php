<?php
use App\Http\Controllers\Admin\DebtCollectionLists\DebtCollectionListsController;
use Illuminate\Support\Facades\Route;
Route::get('/', [DebtCollectionListsController::class, 'index']);
Route::post('/', [DebtCollectionListsController::class, 'storeDebtCollectionList']);
Route::get('/list', [DebtCollectionListsController::class, 'listDebtCollectionLists']);
Route::get('/download-sample', [DebtCollectionListsController::class, 'downloadSample']);
Route::delete('/delete/{id}', [DebtCollectionListsController::class, 'destoryDebtCollectionList']);

