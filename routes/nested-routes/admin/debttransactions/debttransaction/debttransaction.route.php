<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DebtTransactions\DebtTransaction\DebtTransactionController;

Route::get('/view/{id}', [DebtTransactionController::class, 'view']);
Route::any('/update/{id}', [DebtTransactionController::class, 'updateTransaction']);
Route::any('/edit/{id}', [DebtTransactionController::class, 'editTransaction']);
Route::get('/edit/{id}', [DebtTransactionController::class, 'saveEditedTransaction']);



