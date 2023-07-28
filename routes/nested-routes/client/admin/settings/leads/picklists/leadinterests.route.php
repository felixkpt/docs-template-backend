<?php

use App\Http\Controllers\Admin\Settings\Leads\Picklists\LeadInterestsController;
use Illuminate\Support\Facades\Route;

$controller = LeadInterestsController::class;
Route::get('/',[$controller,'index']);
Route::post('/',[$controller,'storeLeadRegion']);
Route::get('/list',[$controller,'listLeadRegions']);
