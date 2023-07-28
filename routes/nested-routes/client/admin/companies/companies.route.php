<?php
use App\Http\Controllers\Admin\Companies\CompaniesController;
use Illuminate\Support\Facades\Route;

$controller = CompaniesController::class;
Route::get('/',[$controller,'index']);
Route::post('/',[$controller,'storeCompany']);
Route::get('/list',[$controller,'listCompanies']);
Route::get('/new-company-modal',[$controller,'newCompanyModalForm']);
Route::get('/edit-company-modal',[$controller,'editCompanyModalForm']);
Route::get('/search',[$controller,'searchCompanies']);
Route::delete('/delete/{company}',[$controller,'destroyCompany']);
Route::get('export-companies',[$controller,'exportCompanies']);
Route::get('/list/company-types',[$controller,'listCompanyTypes']);
Route::get('/companies-export',[$controller,'exportCompanies']);

