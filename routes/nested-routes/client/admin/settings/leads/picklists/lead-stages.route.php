<?php

use App\Http\Controllers\Admin\Settings\Leads\Picklists\LeadStageController;
use Illuminate\Support\Facades\Route;

$controller = LeadStageController::class;
Route::get('/', [$controller, 'index']);
Route::post('/', [$controller, 'storeLeadStage']);
Route::get('/list', [$controller, 'listLeadStages']);
Route::delete('/delete/{lead}', [$controller, 'destroyLead']);
Route::post('/activate/{id}', [$controller, 'activate']);
Route::post('/deactivate/{id}', [$controller, 'deactivate']);
