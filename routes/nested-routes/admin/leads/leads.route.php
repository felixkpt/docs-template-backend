<?php

use App\Http\Controllers\Admin\Leads\LeadsController;
use Illuminate\Support\Facades\Route;


$controller = LeadsController::class;
Route::get('/', [$controller, 'index']);

Route::get('/create',[$controller,'createLead']);
Route::post('/',[$controller,'storeLead']);
Route::get('/list',[$controller,'listLeads']);
Route::delete('/delete/{}',[$controller,'destroy']);
Route::get('/dashboard', [$controller,'dashboard']);
Route::get('stage-count', [$controller , 'leadCount']);
Route::get('products', [$controller , 'products']);
Route::get('users',[ $controller , 'users']);
Route::get('lead-details', [$controller , 'leadDetails']);
Route::get('new-customer', [$controller , 'storeCustomer']);
Route::get('export', [$controller , 'exportLeads']);
Route::get('stages-list', [$controller , 'stagesList']);
Route::get('get-lead-contact', [$controller , 'getLeadContact']);
Route::get('assigned-to-users', [$controller , 'getAssignedToUsers']);
Route::any('/contact-search',[$controller,'searchContacts']);
Route::get('/create-lead',[$controller ,'createNewLead']);
Route::post('tmp',  [$controller , 'saveTmpFiles']);
Route::post('tmp/delete',  [$controller , 'deleteTmpFiles']);
Route::post('add-files', [$controller,'addImages']);
Route::post('image/delete/{id}', [$controller,'deleteImage']);
Route::get('/get-chart-data', [$controller,'getChartData']);
Route::get('/get-chart-data-lead-sources', [$controller,'getChartDataSources']);
