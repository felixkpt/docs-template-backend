<?php
use App\Http\Controllers\Admin\DebtCollectionLists\DebtCollectionList\DebtCollectionRecordsController;
use Illuminate\Support\Facades\Route;
Route::get('/', [DebtCollectionRecordsController::class, 'index']);
Route::post('/',[DebtCollectionRecordsController::class,'storeDebtCollectionRecords']);
Route::post('/import', [DebtCollectionRecordsController::class, 'importDebtCollectionRecords']);
Route::get('/list', [DebtCollectionRecordsController::class, 'listDebtCollectionRecords']);
Route::delete('/delete/{id}', [DebtCollectionRecordsController::class, 'destroyDebtCollectionRecords']);


