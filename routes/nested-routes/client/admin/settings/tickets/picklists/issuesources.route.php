<?php

use App\Http\Controllers\Admin\Settings\Tickets\Picklists\IssueSourcesController;
use Illuminate\Support\Facades\Route;

$controller = IssueSourcesController::class;

Route::get('/', [$controller, 'index']);
Route::post('/', [$controller, 'storeIssueSource']);
Route::get('/list', [$controller, 'listIssueSources']);
Route::delete('/delete/{source}', [$controller, 'destroyIssueSource']);
Route::post('/activate/{id}', [$controller, 'activate']);
Route::post('/deactivate/{id}', [$controller, 'deactivate']);
