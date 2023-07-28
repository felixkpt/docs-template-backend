<?php

use App\Http\Controllers\Admin\Settings\Leads\Picklists\LeadProductController;
use Illuminate\Support\Facades\Route;

$controller = LeadProductController::class;
Route::get('/', [$controller,'index']);
Route::post('/', [$controller,'storeLeadProduct']);
Route::get('/list', [$controller,'listLeadProducts']);
Route::delete('/delete/{}', [$controller,'destroyLeadProduction']);
Route::post('/activate/{id}', [$controller, 'activate']);
Route::post('/deactivate/{id}', [$controller, 'deactivate']);
