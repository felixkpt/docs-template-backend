<?php

use App\Http\Controllers\Admin\Companies\Company\CompanyController;
use Illuminate\Support\Facades\Route;

$controller = CompanyController::class;

Route::get('/{company}',[$controller,'index']);
