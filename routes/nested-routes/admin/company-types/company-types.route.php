<?php

use Illuminate\Support\Facades\Route;

$controller = App\Http\Controllers\Admin\CompanyTypes\CompanyTypesController::class;
Route::get('/', [$controller, 'index']);
Route::post('/', [$controller, 'storeCompanyType']);
Route::get('/list', [$controller, 'listCompanyTypes']);
Route::delete('/delete/{}', [$controller, 'destroy']);
Route::get('/new-company-type-modal',[$controller,'newCompanyTypeForm']);