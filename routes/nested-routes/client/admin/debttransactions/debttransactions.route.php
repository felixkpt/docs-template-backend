<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DebtTransactions\DebtTransactionsController;

Route::get('/', [DebtTransactionsController::class, 'index']);
Route::any('/create', [DebtTransactionsController::class, 'createDebtTransaction']);
Route::any('/contact/add', [DebtTransactionsController::class, 'saveContact']);
Route::get('contact-search', [DebtTransactionsController::class, 'searchContacts']);
Route::delete('/delete/{id}', [DebtTransactionsController::class, 'destoryDebtCollectionList']);
Route::post('/',[DebtTransactionsController::class,'storeDebtCollectionRecords']);
Route::get('/download-sample', [DebtTransactionsController::class, 'downloadSample']);
Route::get('/list', [DebtTransactionsController::class, 'listDebtTransactions']);
Route::get('/delete/{id}', [DebtTransactionsController::class, 'destoryDebtCollectionRecords']);




