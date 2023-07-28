<?php

use App\Http\Controllers\Admin\Settings\Debts\Picklists\DebtStatusController;
use App\Models\Core\DebtStatus;
use Illuminate\Support\Facades\Route;

$controller = DebtStatusController::class;

Route::get('/',[$controller,'index']);
Route::post('/',[$controller,'storeDebtStatus']);
Route::get('/list',[$controller,'listDebtStatus']);
Route::post('/activate/{id}', [$controller, 'activate']);
Route::post('/deactivate/{id}', [$controller, 'deactivate']);
