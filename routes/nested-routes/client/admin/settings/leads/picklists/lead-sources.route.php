<?php

use App\Http\Controllers\Admin\Settings\Leads\Picklists\LeadSourceController;
use Illuminate\Support\Facades\Route;

$controller = LeadSourceController::class;
Route::get('/', [$controller, 'index']);
Route::post('/', [$controller, 'storeLeadSource']);
Route::get('/list', [$controller, 'listLeadSources']);
Route::delete('/delete/{lead}', [$controller, 'destroyLead']);
Route::post('/activate/{id}', [$controller, 'activate']);
Route::post('/deactivate/{id}', [$controller, 'deactivate']);
