<?php

use App\Http\Controllers\Admin\Settings\Debts\Picklists\DebtAgesController;
use Illuminate\Support\Facades\Route;

$controller = DebtAgesController::class;

Route::get('/',[$controller,'index']);
Route::post('/',[$controller,'storeDebtAges']);
Route::get('/list',[$controller,'listDebtAges']);
Route::post('/activate/{id}', [$controller, 'activate']);
Route::post('/deactivate/{id}', [$controller, 'deactivate']);

