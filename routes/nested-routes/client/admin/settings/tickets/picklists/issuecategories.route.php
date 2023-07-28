<?php

use App\Http\Controllers\Admin\Settings\Tickets\Picklists\IssueCategoriesController;
use Illuminate\Support\Facades\Route;

$controller = IssueCategoriesController::class;
Route::get('/',[$controller,'index']);
Route::post('/',[$controller, 'storeIssueCategory']);
Route::get('/list',[$controller,'listIssueCategories']);
Route::delete('/delete/{issuecategory}',[$controller,'destroyIssueCategory']);
Route::get('/dispositions/{issuecategory}',[$controller,'manageDispositionView']);
Route::get('/list/dispositions/{issuecategory}',[$controller,'listDispositions']);
Route::post('/dispositions',[$controller,'addDisposition']);
Route::get('/search',[$controller,'searchCategories']);
Route::post('/remove-disposition/{issuecategory}',[$controller,'removeDisposition']);
Route::post('/activate/{id}',[$controller,'activate']);
Route::post('/deactivate/{id}',[$controller,'deactivate']);
